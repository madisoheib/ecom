@php
$cosmeticImages = [
    'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=400&fit=crop&auto=format&q=90',
    'https://images.unsplash.com/photo-1570194065650-d99fb4bedf0a?w=400&h=400&fit=crop&auto=format&q=90',
    'https://images.unsplash.com/photo-1596755389378-c31d21fd1273?w=400&h=400&fit=crop&auto=format&q=90',
    'https://images.unsplash.com/photo-1629734349343-b047c1850bf9?w=400&h=400&fit=crop&auto=format&q=90',
    'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=400&h=400&fit=crop&auto=format&q=90',
    'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=400&h=400&fit=crop&auto=format&q=90',
    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=400&fit=crop&auto=format&q=90',
    'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=400&h=400&fit=crop&auto=format&q=90',
    'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?w=400&h=400&fit=crop&auto=format&q=90',
    'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=400&h=400&fit=crop&auto=format&q=90',
    'https://images.unsplash.com/photo-1615397349754-cfa2066a298e?w=400&h=400&fit=crop&auto=format&q=90',
    'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=400&fit=crop&auto=format&q=90'
];

$cosmeticNames = [
    'Radiance Glow Serum SPF 30',
    'Hydrating Face Cream',
    'Vitamin C Brightening Serum',
    'Anti-Aging Night Cream',
    'Gentle Cleansing Foam',
    'Moisturizing Face Mask',
    'Eye Contour Treatment',
    'Exfoliating Face Scrub',
    'Nourishing Body Lotion',
    'Lip Repair Balm',
    'Micellar Cleansing Water',
    'Revitalizing Face Toner',
    'Sunscreen Protection SPF 50',
    'Collagen Booster Cream',
    'Acne Treatment Gel',
    'Rose Water Face Mist'
];

// Gradient backgrounds like in the image
$gradientBackgrounds = [
    'linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%)',
    'linear-gradient(135deg, #f9f7f4 0%, #e8ddd3 100%)',
    'linear-gradient(135deg, #f0f0f0 0%, #d9d9d9 100%)',
    'linear-gradient(135deg, #faf8f5 0%, #e5ddd3 100%)',
];

// Get a consistent random index based on product ID
$imageIndex = $product->id % count($cosmeticImages);
$nameIndex = $product->id % count($cosmeticNames);
$bgIndex = $product->id % count($gradientBackgrounds);

// Random rating between 4.0 and 5.0
$rating = 4.0 + (($product->id % 10) / 10);
$reviewCount = 100 + ($product->id * 23) % 400;

// Check if featured (every 3rd product)
$isFeatured = $product->id % 3 == 0;
@endphp

<a href="{{ route('products.show', [$product->categories->first()?->slug ?? 'produits', $product->slug]) }}" class="block h-full">
<div class="relative overflow-hidden transition-all duration-300 hover:transform hover:scale-105 group h-full w-full max-w-sm mx-auto" style="border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
    <div class="h-full flex flex-col bg-white" style="border-radius: 16px;">
        <!-- Wishlist Heart Icon -->
        <button onclick="event.preventDefault(); event.stopPropagation();" class="absolute top-4 left-4 z-20 bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-all duration-300 group/heart">
            <svg class="w-5 h-5 text-gray-400 group-hover/heart:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </button>

        <!-- Featured Badge -->
        @if($isFeatured)
            <span class="absolute top-4 right-4 bg-white text-gray-700 px-3 py-1 rounded-full text-xs font-roboto-medium z-10 shadow-md">
                Featured
            </span>
        @endif

        <!-- Product Image -->
        <div class="block relative">
            <div class="h-48 bg-cover bg-center bg-no-repeat transition-transform duration-500 group-hover:scale-110 rounded-xl m-4"
                 style="background-image: url('{{ $cosmeticImages[$imageIndex] }}');">
            </div>
        </div>

        <div class="p-4 sm:p-5 flex-grow flex flex-col">
            <!-- Brand Name -->
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center font-light">
                @if($product->brand)
                    {{ $product->brand->name }}
                @else
                    Brand Name
                @endif
            </p>
            
            <!-- Product Name -->
            <h3 class="text-sm sm:text-base font-medium text-gray-800 mb-2 line-clamp-2 text-center">
                {{ $cosmeticNames[$nameIndex] }}
            </h3>

            <!-- Rating Stars -->
            <div class="flex items-center gap-2 mb-3">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($rating))
                            <svg class="w-4 h-4 fill-current" style="color: var(--color-secondary);" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @elseif($i == ceil($rating) && $rating - floor($rating) > 0)
                            <svg class="w-4 h-4 fill-current" style="color: var(--color-secondary);" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" opacity="0.5"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @endif
                    @endfor
                </div>
                <span class="text-xs text-gray-500 font-light hidden sm:inline">({{ $reviewCount }} reviews)</span>
            </div>

            <!-- Price and Add to Cart Button - Inline -->
            <div class="mt-auto flex items-center justify-between">
                <!-- Price on Left -->
                @php
                    $userLocation = get_user_country_from_ip();
                    $userCountry = $userLocation['country_code'];
                    $countryPrice = $product->getPriceForCountry($userCountry);
                    $countryCurrency = $product->getCurrencyForCountry($userCountry);
                @endphp
                <span class="text-lg sm:text-2xl font-bold text-gray-900">{{ format_price($countryPrice, $countryCurrency) }}</span>

                <!-- Add to Cart Button on Right -->
                <button onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $product->id }});"
                        class="font-light py-1.5 px-3 sm:py-2 sm:px-4 transition-all duration-300 hover:shadow-lg flex items-center gap-1 sm:gap-2"
                        style="border-radius: 12px; background-color: var(--color-primary); color: var(--color-secondary);" 
                        onmouseover="this.style.backgroundColor='var(--color-secondary)'; this.style.color='var(--color-primary)';" 
                        onmouseout="this.style.backgroundColor='var(--color-primary)'; this.style.color='var(--color-secondary)';">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l1.5-6m0 0h13M9 19a1 1 0 100 2 1 1 0 000-2zm7 0a1 1 0 100 2 1 1 0 000-2z"></path>
                    </svg>
                    <span class="text-xs sm:text-sm">Add</span>
                </button>
            </div>
        </div>
    </div>
</div>
</a>

