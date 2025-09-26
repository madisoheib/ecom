<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\GuestOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RegisterOrderController extends Controller
{
    public function store(Request $request)
    {
        // Check for recent duplicate registration attempts
        $recentUser = User::where('email', $request->email)
            ->orWhere('phone', $request->phone_number)
            ->where('created_at', '>', now()->subMinutes(10))
            ->first();

        if ($recentUser) {
            return response()->json([
                'success' => false,
                'message' => 'A user with this email or phone was recently registered. Please try logging in instead.',
            ], 429);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'country' => 'required|string|max:10',
            'city' => 'nullable|string|max:100',
            'address' => 'required|string|max:1000',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check stock availability
        if ($product->stock_quantity !== null && $product->stock_quantity < $validated['quantity']) {
            throw ValidationException::withMessages([
                'quantity' => 'Insufficient stock available. Only ' . $product->stock_quantity . ' items left.',
            ]);
        }

        // Create user account
        $user = User::create([
            'name' => $validated['full_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone_number'],
            'country' => $validated['country'],
            'city' => $validated['city'],
            'address' => $validated['address'],
        ]);

        // Calculate prices with registration discount
        $unitPrice = $product->sale_price && $product->sale_price < $product->price 
            ? $product->sale_price 
            : $product->price;
            
        $subtotal = $unitPrice * $validated['quantity'];
        $discountPercent = $product->registration_discount ?? 5;
        $discountAmount = $subtotal * ($discountPercent / 100);
        $totalPrice = $subtotal - $discountAmount;

        // Create order
        $order = GuestOrder::create([
            'product_id' => $validated['product_id'],
            'user_id' => $user->id,
            'full_name' => $validated['full_name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'country' => $validated['country'],
            'city' => $validated['city'],
            'quantity' => $validated['quantity'],
            'unit_price' => $unitPrice,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Update product stock
        if ($product->stock_quantity !== null) {
            $product->decrement('stock_quantity', $validated['quantity']);
        }

        // Log the user in automatically
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => trans('Account created and order placed successfully!'),
            'order_number' => $order->order_number,
            'user_id' => $user->id,
            'order_details' => [
                'product_name' => $product->name,
                'quantity' => $validated['quantity'],
                'subtotal' => $subtotal,
                'discount_percent' => $discountPercent,
                'discount_amount' => $discountAmount,
                'total_price' => $totalPrice,
                'currency' => site_currency()
            ]
        ]);
    }
}