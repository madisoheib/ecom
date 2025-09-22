<div class="group relative">
    <a href="{{ route('products.show', [$product->categories->first()?->slug ?? 'produits', $product->slug]) }}"
       class="block">
        <!-- Card Container -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 hover:border-secondary hover:shadow-secondary/20">
            <!-- Image Container -->
            <div class="relative aspect-square overflow-hidden bg-gray-50">
                @php
                    // Real perfume images array
                    $perfumeImages = [
                        'https://images.unsplash.com/photo-1541643600914-78b084683601?w=400&h=400&fit=crop',
                        'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=400&h=400&fit=crop',
                        'https://images.unsplash.com/photo-1594736797933-d0301ba2fe65?w=400&h=400&fit=crop',
                        'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=400&h=400&fit=crop',
                        'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=400&h=400&fit=crop',
                        'https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?w=400&h=400&fit=crop',
                        'https://images.unsplash.com/photo-1528740561666-dc2479dc08ab?w=400&h=400&fit=crop',
                        'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=400&h=400&fit=crop'
                    ];
                    $imageUrl = $perfumeImages[($product->id - 1) % count($perfumeImages)];
                @endphp
                <img src="{{ $imageUrl }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                <!-- Badges -->
                <div class="absolute top-3 left-3 flex flex-col gap-2">
                    @if($product->stock_quantity <= 0)
                        <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-subheading">
                            @t('Out of Stock')
                        </span>
                    @elseif($product->stock_quantity <= 5)
                        <span class="bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-subheading">
                            @t('Low Stock')
                        </span>
                    @endif

                    @if($product->sale_price && $product->sale_price < $product->price)
                        @php
                            $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                        @endphp
                        <span class="bg-primary text-white px-2 py-1 rounded-full text-xs font-subheading">
                            -{{ $discount }}%
                        </span>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center">
                    <div class="opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                        <button onclick="event.preventDefault(); event.stopPropagation(); quickView({{ $product->id }})"
                                class="bg-white text-gray-700 p-3 rounded-full shadow-lg hover:bg-secondary hover:text-primary transition-colors mr-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        <button onclick="event.preventDefault(); event.stopPropagation(); addToWishlist({{ $product->id }})"
                                class="bg-white text-gray-700 p-3 rounded-full shadow-lg hover:bg-secondary hover:text-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-4">
                <!-- Brand -->
                @if($product->brand)
                    <p class="text-xs text-secondary font-subheading uppercase tracking-wide mb-1">
                        {{ $product->brand->name }}
                    </p>
                @endif

                <!-- Name -->
                <h3 class="text-sm font-subheading text-gray-900 mb-2 line-clamp-2 min-h-[40px]">
                    {{ $product->name }}
                </h3>

                <!-- Rating -->
                <div class="flex items-center mb-3">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= 4 ? 'text-secondary' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-500 ml-2">({{ $product->views_count }})</span>
                </div>

                <!-- Price -->
                <div class="flex items-center justify-between mb-3">
                    <div>
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <span class="text-lg font-subheading text-gray-900">
                                {{ site_currency() === 'EUR' ? '€' : '$' }}{{ number_format($product->sale_price, 2) }}
                            </span>
                            <span class="text-sm text-gray-400 line-through ml-1">
                                {{ site_currency() === 'EUR' ? '€' : '$' }}{{ number_format($product->price, 2) }}
                            </span>
                        @else
                            <span class="text-lg font-subheading text-gray-900">
                                {{ site_currency() === 'EUR' ? '€' : '$' }}{{ number_format($product->price, 2) }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Add to Cart Button -->
                <button onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $product->id }}, '{{ addslashes($product->name) }}')"
                        class="w-full bg-primary text-white py-2.5 rounded-lg font-subheading hover:bg-primary-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
                        {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                    @if($product->stock_quantity <= 0)
                        @t('Out of Stock')
                    @else
                        @t('Add to Cart')
                    @endif
                </button>
            </div>
        </div>
    </a>
</div>