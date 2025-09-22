<a href="{{ route('products.show', [$product->categories->first()?->slug ?? 'produits', $product->slug]) }}"
   class="block bg-white rounded-xl overflow-hidden group hover:shadow-xl transition-all duration-300 cursor-pointer border border-gray-100 hover:border-secondary hover:shadow-secondary/20">
    <div class="relative overflow-hidden">
        @php
            // Real perfume images array
            $perfumeImages = [
                'https://images.unsplash.com/photo-1541643600914-78b084683601?w=300&h=200&fit=crop',
                'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=300&h=200&fit=crop',
                'https://images.unsplash.com/photo-1594736797933-d0301ba2fe65?w=300&h=200&fit=crop',
                'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=300&h=200&fit=crop',
                'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=300&h=200&fit=crop',
                'https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?w=300&h=200&fit=crop',
                'https://images.unsplash.com/photo-1528740561666-dc2479dc08ab?w=300&h=200&fit=crop',
                'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=300&h=200&fit=crop'
            ];
            $imageUrl = $perfumeImages[($product->id - 1) % count($perfumeImages)];
        @endphp

        <img src="{{ $imageUrl }}"
             alt="{{ $product->name }}"
             class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">

        <!-- Quick Actions -->
        <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-1 group-hover:translate-y-0">
            <button onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $product->id }}, '{{ addslashes($product->name) }}')"
                    class="bg-white p-2.5 rounded-full shadow-lg hover:shadow-xl hover:bg-secondary hover:text-primary transition-all duration-300 border border-gray-100 hover:border-secondary">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                </svg>
            </button>
        </div>
        
        <!-- Stock Status -->
        @if($product->stock_quantity <= 0)
            <div class="absolute top-3 left-3 bg-red-500 text-white px-2.5 py-1 text-xs font-subheading rounded-full shadow-lg">
                @t('Out of Stock')
            </div>
        @elseif($product->stock_quantity <= 5)
            <div class="absolute top-3 left-3 bg-orange-500 text-white px-2.5 py-1 text-xs font-subheading rounded-full shadow-lg">
                @t('Low Stock')
            </div>
        @endif

        <!-- Discount Badge -->
        @if($product->compare_price && $product->compare_price > $product->price)
            @php
                $discount = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
            @endphp
            <div class="absolute top-3 left-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-2.5 py-1 text-xs font-heading rounded-full shadow-lg">
                -{{ $discount }}%
            </div>
        @endif
    </div>
    
    <div class="p-5">
        <!-- Brand -->
        @if($product->brand)
            <p class="text-sm text-secondary font-subheading mb-2">{{ $product->brand->name }}</p>
        @endif

        <!-- Product Name -->
        <h3 class="font-subheading text-gray-900 mb-3 line-clamp-2 group-hover:text-primary transition-colors">
            {{ $product->name }}
        </h3>

        <!-- Price -->
        <div class="flex items-center space-x-2 mb-4">
            <span class="text-xl font-heading text-gray-900">{{ site_currency() === 'EUR' ? '€' : '$' }}{{ number_format($product->price, 2) }}</span>
            @if($product->compare_price && $product->compare_price > $product->price)
                <span class="text-sm text-gray-500 line-through">{{ site_currency() === 'EUR' ? '€' : '$' }}{{ number_format($product->compare_price, 2) }}</span>
            @endif
        </div>
        
        <!-- Rating (placeholder) -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-1">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= 4 ? 'text-secondary' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                @endfor
            </div>
            <span class="text-xs text-gray-500 font-subheading">({{ $product->views_count }})</span>
        </div>

        <!-- Add to Cart Button -->
        <button onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $product->id }}, '{{ addslashes($product->name) }}')"
                class="w-full text-white py-3 px-6 rounded-xl font-subheading transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-primary-300 shadow-lg hover:shadow-xl {{ $product->stock_quantity <= 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-gradient-primary hover:bg-primary-700' }}"
                {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
            @if($product->stock_quantity <= 0)
                <div class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                    </svg>
                    @t('Out of Stock')
                </div>
            @else
                <div class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                    </svg>
                    @t('Add to Cart')
                </div>
            @endif
        </button>
    </div>
</a>

@push('scripts')
<script>
function addToCart(productId) {
    fetch('{{ route('cart.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            window.cartData.count = data.cart_count;
            window.cartData.total = data.cart_total;
            
            // Show success message (you can implement a toast notification here)
            alert('@t('Product added to cart!')');
        } else {
            alert(data.message || '@t('Failed to add product to cart')');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('@t('An error occurred. Please try again.')');
    });
}
</script>
@endpush