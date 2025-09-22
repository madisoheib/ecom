@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-4 py-12">
        <!-- Simple Centered Product Display -->
        <div class="text-center space-y-8">
            <!-- Product Image -->
            <div class="relative mx-auto w-full max-w-md">
                @php
                    // Real perfume images array
                    $perfumeImages = [
                        'https://images.unsplash.com/photo-1541643600914-78b084683601?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1594736797933-d0301ba2fe65?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1528740561666-dc2479dc08ab?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=500&h=500&fit=crop'
                    ];
                    $imageUrl = $perfumeImages[($product->id - 1) % count($perfumeImages)];
                @endphp
                <img src="{{ $imageUrl }}"
                     alt="{{ $product->name }}"
                     class="w-full h-96 object-cover rounded-2xl shadow-sm">

                @if($product->stock_quantity <= 5 && $product->stock_quantity > 0)
                <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-xs font-subheading text-orange-600 shadow-sm">
                    Only {{ $product->stock_quantity }} left
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-4">
                <!-- Title -->
                <h1 class="text-3xl font-heading text-gray-900">{{ $product->name }}</h1>

                <!-- Price -->
                <div class="text-2xl font-light text-gray-900">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span>{{ site_currency() === 'EUR' ? '€' : '$' }}{{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-lg text-gray-400 line-through ml-2">{{ site_currency() === 'EUR' ? '€' : '$' }}{{ number_format($product->price, 2) }}</span>
                    @else
                        <span>{{ site_currency() === 'EUR' ? '€' : '$' }}{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Description -->
                @if($product->description)
                <p class="text-gray-600 font-light max-w-2xl mx-auto leading-relaxed">
                    {{ $product->description }}
                </p>
                @endif
            </div>
        </div>

        <!-- Simple Checkout Form -->
        <div class="mt-16 max-w-md mx-auto">
            <form id="quickCheckout" method="POST" action="{{ route('guest-order.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- Quantity -->
                <div>
                    <label class="text-sm font-subheading text-gray-700 mb-2 block">Quantity</label>
                    <div class="flex items-center justify-center space-x-4">
                        <button type="button" onclick="decreaseQty()"
                                class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity ?? 100 }}"
                               class="w-20 text-center border-0 border-b border-gray-300 focus:border-primary focus:outline-none">
                        <button type="button" onclick="increaseQty()"
                                class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="space-y-4">
                    <div>
                        <input type="text" name="full_name" required
                               placeholder="Your name"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors">
                    </div>

                    <div>
                        <input type="tel" name="phone_number" required
                               placeholder="Phone number"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors">
                    </div>

                    <div>
                        <input type="email" name="email"
                               placeholder="Email (optional)"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors">
                    </div>

                    <div>
                        <textarea name="address" required rows="2"
                                  placeholder="Delivery address"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors resize-none"></textarea>
                    </div>
                </div>

                <!-- Total -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center text-lg">
                        <span class="font-subheading text-gray-700">Total</span>
                        <span class="font-subheading text-gray-900">{{ site_currency() === 'EUR' ? '€' : '$' }}<span id="totalPrice">{{ number_format($product->price, 2) }}</span></span>
                    </div>
                </div>

                <!-- Submit Button -->
                @if($product->stock_quantity > 0)
                <button type="submit"
                        class="w-full bg-primary text-white py-4 rounded-lg font-subheading hover:bg-primary-700 transition-colors">
                    Place Order
                </button>
                @else
                <button type="button" disabled
                        class="w-full bg-gray-200 text-gray-400 py-4 rounded-lg font-subheading cursor-not-allowed">
                    Out of Stock
                </button>
                @endif

                <!-- Trust Badge -->
                <div class="text-center space-y-2">
                    <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Secure
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Fast Delivery
                        </span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Related Products (Minimal) -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="mt-24">
            <h2 class="text-center text-xl font-subheading text-gray-900 mb-8">You might also like</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts->take(4) as $relatedProduct)
                <a href="{{ route('products.show', [$relatedProduct->categories->first()?->slug ?? 'produits', $relatedProduct->slug]) }}"
                   class="group">
                    @php
                        $perfumeImages = [
                            'https://images.unsplash.com/photo-1541643600914-78b084683601?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1594736797933-d0301ba2fe65?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1528740561666-dc2479dc08ab?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=200&h=200&fit=crop'
                        ];
                        $relatedImageUrl = $perfumeImages[($relatedProduct->id - 1) % count($perfumeImages)];
                    @endphp
                    <img src="{{ $relatedImageUrl }}"
                         alt="{{ $relatedProduct->name }}"
                         class="w-full h-32 object-cover rounded-lg mb-2 group-hover:opacity-80 transition-opacity">
                    <h3 class="text-sm font-subheading text-gray-900 truncate">{{ $relatedProduct->name }}</h3>
                    <p class="text-sm text-gray-600">{{ site_currency() === 'EUR' ? '€' : '$' }}{{ number_format($relatedProduct->price, 2) }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
const productPrice = {{ $product->price }};

function updateTotal() {
    const quantity = document.getElementById('quantity').value;
    const total = (productPrice * quantity).toFixed(2);
    document.getElementById('totalPrice').textContent = total;
}

function increaseQty() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
        updateTotal();
    }
}

function decreaseQty() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
        updateTotal();
    }
}

document.getElementById('quantity').addEventListener('input', updateTotal);

// Simple form submission
document.getElementById('quickCheckout').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Simple success message
            alert('Order placed successfully! Order #' + data.order_number);
            this.reset();
            updateTotal();
        } else {
            alert('Please check your information and try again.');
        }
    })
    .catch(error => {
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});
</script>
@endsection