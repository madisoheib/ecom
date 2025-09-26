<?php

namespace App\Http\Controllers;

use App\Models\GuestOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GuestOrderController extends Controller
{
    public function store(Request $request)
    {
        // Bot protection: Check for duplicate orders in the last 5 minutes
        $recentOrder = GuestOrder::where('phone_number', $request->phone_number)
            ->where('product_id', $request->product_id)
            ->where('created_at', '>', now()->subMinutes(5))
            ->first();

        if ($recentOrder) {
            return response()->json([
                'success' => false,
                'message' => 'You have already placed an order for this product recently. Please wait before ordering again.',
            ], 429);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:1000',
            'quantity' => 'required|integer|min:1|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check stock availability
        if ($product->stock_quantity !== null && $product->stock_quantity < $validated['quantity']) {
            throw ValidationException::withMessages([
                'quantity' => 'Insufficient stock available. Only ' . $product->stock_quantity . ' items left.',
            ]);
        }

        $unitPrice = $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price;
        $totalPrice = $unitPrice * $validated['quantity'];

        $guestOrder = GuestOrder::create([
            'product_id' => $validated['product_id'],
            'full_name' => $validated['full_name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'quantity' => $validated['quantity'],
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        // Update product stock if available
        if ($product->stock_quantity !== null) {
            $product->decrement('stock_quantity', $validated['quantity']);
        }

        return response()->json([
            'success' => true,
            'message' => trans('Order placed successfully!'),
            'order_number' => $guestOrder->order_number,
            'order_details' => [
                'product_name' => $product->name,
                'quantity' => $validated['quantity'],
                'total_price' => $totalPrice,
                'currency' => site_currency()
            ]
        ]);
    }

    public function refreshCaptcha()
    {
        return response()->json([
            'captcha' => captcha_img('flat')
        ]);
    }
}
