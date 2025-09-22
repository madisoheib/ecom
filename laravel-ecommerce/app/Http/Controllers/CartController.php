<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:99'
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        // Check stock
        if ($product->stock_quantity < $quantity) {
            return response()->json([
                'success' => false,
                'message' => __('Not enough stock available')
            ]);
        }

        // Get current cart from session
        $cart = session()->get('cart', []);

        // If product already in cart, increase quantity
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            // Add new product to cart
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->images->first()?->url ?? null
            ];
        }

        // Update session
        session()->put('cart', $cart);

        // Calculate totals
        $cartCount = array_sum(array_column($cart, 'quantity'));
        $cartTotal = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        session()->put('cart_count', $cartCount);
        session()->put('cart_total', $cartTotal);

        return response()->json([
            'success' => true,
            'message' => __('Product added to cart successfully'),
            'cart_count' => $cartCount,
            'cart_total' => $cartTotal
        ]);
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $cartTotal = session()->get('cart_total', 0);

        return view('cart.index', compact('cart', 'cartTotal'));
    }

    public function getCartData()
    {
        $cart = session()->get('cart', []);
        $cartTotal = session()->get('cart_total', 0);
        $cartCount = session()->get('cart_count', 0);

        // Format cart items for JSON response
        $cartItems = [];
        $perfumeImages = [
            'https://images.unsplash.com/photo-1541643600914-78b084683601?w=80&h=80&fit=crop',
            'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=80&h=80&fit=crop',
            'https://images.unsplash.com/photo-1594736797933-d0301ba2fe65?w=80&h=80&fit=crop',
            'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=80&h=80&fit=crop',
            'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=80&h=80&fit=crop',
            'https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?w=80&h=80&fit=crop',
            'https://images.unsplash.com/photo-1528740561666-dc2479dc08ab?w=80&h=80&fit=crop',
            'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=80&h=80&fit=crop'
        ];

        foreach ($cart as $id => $item) {
            $cartItems[] = [
                'id' => $id,
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
                'image' => $perfumeImages[($id - 1) % count($perfumeImages)]
            ];
        }

        return response()->json([
            'success' => true,
            'cart' => $cartItems,
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);

            // Recalculate totals
            $cartCount = array_sum(array_column($cart, 'quantity'));
            $cartTotal = array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));

            session()->put('cart_count', $cartCount);
            session()->put('cart_total', $cartTotal);
        }

        return response()->json([
            'success' => true,
            'cart_count' => session()->get('cart_count', 0),
            'cart_total' => session()->get('cart_total', 0)
        ]);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);

            // Recalculate totals
            $cartCount = array_sum(array_column($cart, 'quantity'));
            $cartTotal = array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));

            session()->put('cart_count', $cartCount);
            session()->put('cart_total', $cartTotal);
        }

        return response()->json([
            'success' => true,
            'cart_count' => session()->get('cart_count', 0),
            'cart_total' => session()->get('cart_total', 0)
        ]);
    }

    public function clear()
    {
        session()->forget(['cart', 'cart_count', 'cart_total']);

        return response()->json([
            'success' => true,
            'cart_count' => 0,
            'cart_total' => 0
        ]);
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        $cartTotal = session()->get('cart_total', 0);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('cart.checkout', compact('cart', 'cartTotal'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'country' => 'required|string',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer'
        ]);

        $cart = session()->get('cart', []);
        $cartTotal = session()->get('cart_total', 0);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // For now, just clear the cart and redirect to a success page
        // In a real application, you would:
        // 1. Create an order record
        // 2. Process payment
        // 3. Update inventory
        // 4. Send confirmation emails

        session()->forget(['cart', 'cart_count', 'cart_total']);

        return redirect()->route('checkout.confirmation', ['order' => 'demo-' . time()])
            ->with('success', 'Your order has been placed successfully!');
    }

    public function confirmation($order)
    {
        return view('cart.confirmation', compact('order'));
    }
}
