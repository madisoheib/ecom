@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <div class="container mx-auto px-6 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">@t('Shopping Cart')</h1>
            <nav class="text-sm text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-primary">@t('Home')</a>
                <span class="mx-2">></span>
                <span class="text-gray-900">@t('Shopping Cart')</span>
            </nav>
        </div>

        @if(count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6" style="border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">@t('Cart Items')</h2>

                        <div class="space-y-6">
                            @foreach($cart as $id => $item)
                                <div class="flex items-center space-x-4 pb-6 border-b border-gray-200 last:border-b-0">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @php
                                            $perfumeImages = [
                                                'https://images.unsplash.com/photo-1541643600914-78b084683601?w=100&h=100&fit=crop',
                                                'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=100&h=100&fit=crop',
                                                'https://images.unsplash.com/photo-1594736797933-d0301ba2fe65?w=100&h=100&fit=crop',
                                                'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=100&h=100&fit=crop',
                                                'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=100&h=100&fit=crop',
                                                'https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?w=100&h=100&fit=crop',
                                                'https://images.unsplash.com/photo-1528740561666-dc2479dc08ab?w=100&h=100&fit=crop',
                                                'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=100&h=100&fit=crop'
                                            ];
                                            $imageUrl = $perfumeImages[($id - 1) % count($perfumeImages)];
                                        @endphp
                                        <img src="{{ $imageUrl }}"
                                             alt="{{ $item['name'] }}"
                                             class="w-20 h-20 object-cover rounded-lg">
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 mb-2">{{ $item['name'] }}</h3>
                                        <p class="text-lg font-bold text-primary">${{ number_format($item['price'], 2) }}</p>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-3">
                                        <button onclick="updateQuantity({{ $id }}, {{ $item['quantity'] - 1 }})"
                                                class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 transition-colors"
                                                style="border-radius: 8px;"
                                                {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>

                                        <span class="font-semibold text-gray-900 min-w-[2rem] text-center">{{ $item['quantity'] }}</span>

                                        <button onclick="updateQuantity({{ $id }}, {{ $item['quantity'] + 1 }})"
                                                class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 transition-colors"
                                                style="border-radius: 8px;">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                    </div>

                                    <!-- Remove Button -->
                                    <button onclick="removeFromCart({{ $id }})"
                                            class="text-red-500 hover:text-red-700 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 sticky top-6" style="border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">@t('Order Summary')</h2>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">@t('Subtotal')</span>
                                <span class="font-semibold">${{ number_format($cartTotal, 2) }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600">@t('Shipping')</span>
                                <span class="font-semibold text-green-600">@t('Free')</span>
                            </div>

                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">@t('Total')</span>
                                    <span class="text-2xl font-bold" class="text-primary">${{ number_format($cartTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <a href="{{ route('checkout') }}"
                               class="w-full text-white font-semibold py-3 px-4 text-center block transition-all duration-200 hover:scale-105 hover:shadow-lg"
                               class="bg-primary hover:bg-primary-700" style="border-radius: 12px;">
                                @t('Proceed to Checkout')
                            </a>

                            <a href="{{ url('/') }}"
                               class="w-full bg-gray-100 text-gray-900 font-medium py-3 px-4 text-center block hover:bg-gray-200 transition-colors"
                               style="border-radius: 12px;">
                                @t('Continue Shopping')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="bg-white p-12 text-center" style="border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <svg class="w-24 h-24 mx-auto mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">@t('Your cart is empty')</h2>
                <p class="text-gray-600 mb-8">@t('Add some products to get started')</p>
                <a href="{{ url('/') }}"
                   class="inline-flex items-center px-8 py-3 text-white font-semibold transition-all duration-200 hover:scale-105 hover:shadow-lg"
                   class="bg-primary hover:bg-primary-700" style="border-radius: 12px;">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    @t('Start Shopping')
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function updateQuantity(productId, newQuantity) {
    if (newQuantity < 1) return;

    fetch(`{{ route('cart.update', '') }}/${productId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('@t('An error occurred. Please try again.')');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('@t('An error occurred. Please try again.')');
    });
}

function removeFromCart(productId) {
    if (confirm('@t('Are you sure you want to remove this item?')')) {
        fetch(`{{ route('cart.remove', '') }}/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('@t('An error occurred. Please try again.')');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('@t('An error occurred. Please try again.')');
        });
    }
}
</script>
@endsection