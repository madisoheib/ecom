<?php

namespace App\Http\Controllers;

use App\Models\GuestOrder;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GuestOrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'region_id' => 'nullable|exists:regions,id',
            'address' => 'required|string|max:1000',
            'quantity' => 'required|integer|min:1|max:100',
            'notes' => 'nullable|string|max:500',
            'captcha' => 'required|captcha',
        ], [
            'captcha.required' => 'Please complete the captcha verification.',
            'captcha.captcha' => 'Captcha verification failed. Please try again.',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check stock availability
        if ($product->track_quantity && $product->quantity < $validated['quantity']) {
            throw ValidationException::withMessages([
                'quantity' => 'Insufficient stock available. Only ' . $product->quantity . ' items left.',
            ]);
        }

        $unitPrice = $product->price;
        $totalPrice = $unitPrice * $validated['quantity'];

        $guestOrder = GuestOrder::create([
            'product_id' => $validated['product_id'],
            'region_id' => $validated['region_id'],
            'full_name' => $validated['full_name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'quantity' => $validated['quantity'],
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        // Update product stock if tracking is enabled
        if ($product->track_quantity) {
            $product->decrement('quantity', $validated['quantity']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully! Your order number is: ' . $guestOrder->order_number,
            'order_number' => $guestOrder->order_number,
        ]);
    }

    public function refreshCaptcha()
    {
        return response()->json([
            'captcha' => captcha_img('flat')
        ]);
    }
}
