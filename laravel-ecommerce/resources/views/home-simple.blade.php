@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Modern Creative Hero Slider -->
    <section class="relative py-8">
        <div class="container mx-auto px-4">
            <div id="modernSlider" class="relative h-[500px] rounded-3xl overflow-hidden shadow-2xl">
                @php
                $beautySlides = [
                    [
                        'image' => 'https://images.unsplash.com/photo-1596755389378-c31d21fd1273?w=1400&h=500&fit=crop&auto=format&q=90',
                        'title' => 'Discover Beauty Excellence',
                        'subtitle' => 'Premium Skincare & Cosmetics',
                        'description' => 'Transform your daily routine with our carefully curated collection of luxury beauty products.',
                        'button_text' => 'Shop Beauty',
                        'gradient' => 'from-black/60 via-black/40 to-transparent'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=1400&h=500&fit=crop&auto=format&q=90',
                        'title' => 'Radiant Skin Solutions',
                        'subtitle' => 'Advanced Skincare Technology',
                        'description' => 'Experience the power of science-backed formulations for healthy, glowing skin.',
                        'button_text' => 'Explore Skincare',
                        'gradient' => 'from-blue-900/60 via-blue-800/40 to-transparent'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=1400&h=500&fit=crop&auto=format&q=90',
                        'title' => 'Luxury Beauty Collection',
                        'subtitle' => 'Curated for You',
                        'description' => 'Indulge in premium beauty essentials from the world\'s most trusted brands.',
                        'button_text' => 'View Collection',
                        'gradient' => 'from-purple-900/60 via-purple-800/40 to-transparent'
                    ]
                ];
                @endphp

                @foreach($beautySlides as $index => $slide)
                    <div class="slide-item absolute inset-0 transition-all duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-105' }}"
                         data-slide="{{ $index }}">
                        <div class="relative w-full h-full">
                            <!-- Background Image -->
                            <img src="{{ $slide['image'] }}"
                                 alt="{{ $slide['title'] }}"
                                 class="w-full h-full object-cover">

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-r {{ $slide['gradient'] }}"></div>

                            <!-- Content -->
                            <div class="absolute inset-0 flex items-center">
                                <div class="container mx-auto px-8 relative z-10">
                                    <div class="max-w-2xl">
                                        <!-- Animated Content Card -->
                                        <div class="backdrop-blur-sm bg-white/10 rounded-2xl p-8 border border-white/20 shadow-2xl transform transition-all duration-700 hover:scale-105">
                                            <div class="space-y-6">
                                                <!-- Title -->
                                                <h1 class="text-4xl md:text-6xl font-roboto-black text-white leading-tight">
                                                    {{ $slide['title'] }}
                                                </h1>

                                                <!-- Description -->
                                                <p class="text-lg md:text-xl text-white/90 font-roboto-light leading-relaxed">
                                                    {{ $slide['description'] }}
                                                </p>

                                                <!-- Button -->
                                                <div>
                                                    <a href="{{ route('products.index') }}"
                                                       class="inline-flex items-center px-8 py-4 border-2 border-white text-white font-roboto-light text-lg rounded-xl hover:bg-white hover:text-black transition-all duration-300 transform hover:-translate-y-1">
                                                        {{ $slide['button_text'] }}
                                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Modern Navigation -->
                <button id="prevSlide" class="absolute left-6 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-4 rounded-2xl transition-all duration-300 z-30 shadow-xl hover:shadow-2xl hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button id="nextSlide" class="absolute right-6 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-4 rounded-2xl transition-all duration-300 z-30 shadow-xl hover:shadow-2xl hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Modern Dots Indicator -->
                <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-3 z-30">
                    @foreach($beautySlides as $index => $slide)
                        <button class="slider-dot w-4 h-4 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-secondary shadow-lg' : 'bg-white/50 hover:bg-white/70' }} hover:scale-125"
                                data-slide="{{ $index }}"></button>
                    @endforeach
                </div>

                <!-- Floating Elements -->
                <div class="absolute top-8 right-8 z-20">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                        <div class="flex items-center space-x-2 text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="text-sm font-medium">Premium Quality</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Grid - Enhanced Visibility -->
    @if($categories && $categories->count() > 0)
    <section class="py-12 sm:py-16 md:py-20 lg:py-24 bg-gradient-to-br from-gray-50 to-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-heading text-gray-900 text-center mb-2 sm:mb-4">@t('Shop by Category')</h2>
            <p class="text-sm sm:text-base md:text-lg text-gray-600 text-center mb-8 sm:mb-12 md:mb-16">@t('Discover our exclusive fragrance collections')</p>
            @php
                $gradientBackgrounds = [
                    'linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%)',
                    'linear-gradient(135deg, #f9f7f4 0%, #e8ddd3 100%)',
                    'linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%)',
                    'linear-gradient(135deg, #fefefe 0%, #f0f0f0 100%)',
                    'linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%)',
                    'linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%)',
                ];

                $categoryImages = [
                    'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=400&h=300&fit=crop&crop=center',
                    'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400&h=300&fit=crop&crop=center',
                    'https://images.unsplash.com/photo-1541643600914-78b084683601?w=400&h=300&fit=crop&crop=center',
                    'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=300&fit=crop&crop=center',
                    'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=400&h=300&fit=crop&crop=center',
                    'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=400&h=300&fit=crop&crop=center',
                ];
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6 md:gap-8 max-w-7xl mx-auto">
                @foreach($categories as $index => $category)
                <div class="group text-center">
                    <a href="{{ route('categories.show', $category->slug) }}" class="block">
                        <!-- Simple Icon Container -->
                        <div class="flex justify-center mb-4">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white border-2 border-gray-200 rounded-full flex items-center justify-center group-hover:scale-110 transition-all duration-300 group-hover:border-black">
                                @php
                                    $iconClasses = 'w-8 h-8 sm:w-10 sm:h-10 text-gray-700 group-hover:text-black transition-colors duration-300';
                                    $iconIndex = $index % 8;
                                @endphp

                        @if($iconIndex == 0)
                            <!-- Perfume Bottle -->
                            <svg class="{{ $iconClasses }}" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L14 4H10L12 2Z"/>
                                <path d="M8 6H16L17 20C17 20.5 16.5 21 16 21H8C7.5 21 7 20.5 7 20L8 6Z"/>
                                <circle cx="12" cy="12" r="2"/>
                            </svg>
                        @elseif($iconIndex == 1)
                            <!-- Star -->
                            <svg class="{{ $iconClasses }}" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L14.09 8.26L20.82 8.45L15.7 12.6L17.45 19L12 15.4L6.55 19L8.3 12.6L3.18 8.45L9.91 8.26L12 2Z"/>
                            </svg>
                        @elseif($iconIndex == 2)
                            <!-- Heart -->
                            <svg class="{{ $iconClasses }}" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 21.35L10.55 20.03C5.4 15.36 2 12.27 2 8.5C2 5.41 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.08C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.41 22 8.5C22 12.27 18.6 15.36 13.45 20.03L12 21.35Z"/>
                            </svg>
                        @elseif($iconIndex == 3)
                            <!-- Diamond -->
                            <svg class="{{ $iconClasses }}" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6 3L2 9L12 21L22 9L18 3H6Z"/>
                            </svg>
                        @elseif($iconIndex == 4)
                            <!-- Gift Box -->
                            <svg class="{{ $iconClasses }}" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4 7H20V11H4V7Z"/>
                                <path d="M5 12H11V21H5V12Z"/>
                                <path d="M13 12H19V21H13V12Z"/>
                                <path d="M12 2C10.5 2 9.5 3 9.5 4C9.5 5 10.5 6 12 6C13.5 6 14.5 5 14.5 4C14.5 3 13.5 2 12 2Z"/>
                            </svg>
                        @elseif($iconIndex == 5)
                            <!-- Flower -->
                            <svg class="{{ $iconClasses }}" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="12" cy="8" r="3"/>
                                <circle cx="8" cy="12" r="2"/>
                                <circle cx="16" cy="12" r="2"/>
                                <circle cx="12" cy="16" r="2"/>
                                <path d="M12 11V22"/>
                            </svg>
                        @elseif($iconIndex == 6)
                            <!-- Sparkle -->
                            <svg class="{{ $iconClasses }}" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0L14 10L24 12L14 14L12 24L10 14L0 12L10 10Z"/>
                            </svg>
                        @else
                            <!-- Circle Pattern -->
                            <svg class="{{ $iconClasses }}" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="12" cy="12" r="8"/>
                                <circle cx="12" cy="12" r="4" fill="rgba(0,0,0,0.3)"/>
                            </svg>
                        @endif
                            </div>
                        </div>

                        <!-- Category Name -->
                        <h3 class="text-sm sm:text-base font-roboto-medium text-gray-900 group-hover:text-black transition-colors duration-300 mb-1">
                            {{ $category->name }}
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-500 font-roboto-light group-hover:text-gray-700 transition-colors">
                            @t('Explore Collection')
                        </p>
                    </a>
                </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Products - Minimal Cards -->
    @if($featuredProducts && $featuredProducts->count() > 0)
    <section class="py-8 sm:py-12 md:py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8 sm:mb-10 md:mb-12">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-heading text-gray-900 mb-2">@t('Featured Products')</h2>
                <p class="text-sm sm:text-base text-gray-600 font-light">@t('Hand-picked selections just for you')</p>
            </div>

            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                    @foreach($featuredProducts as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>

            <div class="text-center mt-8 sm:mt-10 md:mt-12">
                <a href="{{ route('products.index', ['featured' => 1]) }}"
                   class="inline-flex items-center text-sm sm:text-base text-primary hover:text-primary-700 font-medium">
                    @t('View All Products')
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Benefits Section - Clean Icons -->
    <section class="py-8 sm:py-12 md:py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8">
                <div class="text-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary-50 rounded-lg flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-subheading text-gray-900 mb-2">@t('Fast Delivery')</h3>
                    <p class="text-sm sm:text-base text-gray-600 font-light">@t('Get your orders delivered within 24-48 hours')</p>
                </div>

                <div class="text-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-50 rounded-lg flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-subheading text-gray-900 mb-2">@t('Quality Guaranteed')</h3>
                    <p class="text-sm sm:text-base text-gray-600 font-light">@t('100% authentic products with warranty')</p>
                </div>

                <div class="text-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-subheading text-gray-900 mb-2">@t('Easy Returns')</h3>
                    <p class="text-sm sm:text-base text-gray-600 font-light">@t('30-day return policy for your peace of mind')</p>
                </div>
            </div>
        </div>
    </section>

    <!-- New Arrivals - Clean Grid -->
    @if($recentProducts && $recentProducts->count() > 0)
    <section class="py-8 sm:py-12 md:py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8 sm:mb-10 md:mb-12">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-heading text-gray-900 mb-2">@t('New Arrivals')</h2>
                <p class="text-sm sm:text-base text-gray-600 font-light">@t('Latest additions to our collection')</p>
            </div>

            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                    @foreach($recentProducts->take(8) as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Newsletter - Minimal Design -->
    <section class="py-8 sm:py-12 md:py-16 bg-white border-t border-gray-200">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-heading text-gray-900 mb-2">@t('Stay Updated')</h2>
                <p class="text-sm sm:text-base text-gray-600 font-light mb-6 sm:mb-8">@t('Subscribe to get special offers and exclusive updates')</p>

                <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    <input type="email"
                           placeholder="@t('Enter your email')"
                           class="flex-1 px-4 py-3 text-sm sm:text-base border border-gray-200 rounded-lg focus:border-primary focus:outline-none">
                    <button type="submit"
                            class="px-6 py-3 text-sm sm:text-base bg-primary text-white rounded-lg hover:bg-primary-700 transition-colors font-subheading whitespace-nowrap">
                        @t('Subscribe')
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
function addToCart(productId, productName = '') {
    // Show loading state if we have a button
    const addButtons = document.querySelectorAll(`[onclick*="addToCart(${productId})"]`);
    addButtons.forEach(btn => {
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
    });

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
            // Update cart count in navbar
            const cartCountElements = document.querySelectorAll('[x-text="window.cartData.count"], .cart-count');
            cartCountElements.forEach(element => {
                element.textContent = data.cart_count || data.count;
            });

            // Update window cart data
            if (window.cartData) {
                window.cartData.count = data.cart_count || data.count;
                window.cartData.total = data.cart_total || data.total;
            }

            // Show beautiful success notification
            const message = productName ? `${productName} a été ajouté au panier avec succès!` : 'Produit ajouté au panier avec succès!';
            showNotification(message, 'success', productName);

            // Reset button state
            addButtons.forEach(btn => {
                btn.disabled = false;
                btn.innerHTML = 'Ajouter au panier';
            });
        } else {
            showNotification(data.message || 'Erreur lors de l\'ajout au panier', 'error');

            // Reset button state
            addButtons.forEach(btn => {
                btn.disabled = false;
                btn.innerHTML = 'Ajouter au panier';
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Une erreur s\'est produite. Veuillez réessayer.', 'error');

        // Reset button state
        addButtons.forEach(btn => {
            btn.disabled = false;
            btn.innerHTML = 'Ajouter au panier';
        });
    });
}

function quickView(productId) {
    // Placeholder for quick view functionality
    console.log('Quick view for product:', productId);
}

function addToWishlist(productId) {
    // Placeholder for wishlist functionality
    showNotification('@t("Added to wishlist")', 'success');
}

function showNotification(message, type = 'info', productName = '') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 w-80 rounded-xl shadow-2xl z-50 transform translate-x-full transition-all duration-500 border`;

    let bgClass, iconSvg, titleText;

    if (type === 'success') {
        bgClass = 'bg-white border-secondary';
        titleText = productName ? `Ajouté au panier` : 'Succès';
        iconSvg = `<svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
        </svg>`;
    } else if (type === 'error') {
        bgClass = 'bg-white border-red-500';
        titleText = 'Erreur';
        iconSvg = `<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>`;
    } else {
        bgClass = 'bg-white border-primary';
        titleText = 'Information';
        iconSvg = `<svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>`;
    }

    notification.className += ` ${bgClass}`;

    notification.innerHTML = `
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${iconSvg}
                </div>
                <div class="ml-3 w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-900">${titleText}</p>
                    <p class="mt-1 text-sm text-gray-600">${message}</p>
                    ${type === 'success' && productName ? `
                        <div class="mt-2 flex space-x-2">
                            <a href="/panier" class="text-xs bg-secondary text-primary px-3 py-1 rounded-full font-medium hover:bg-yellow-400 transition-colors">
                                Voir le panier
                            </a>
                            <button onclick="this.closest('.fixed').remove()" class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-medium hover:bg-gray-200 transition-colors">
                                Fermer
                            </button>
                        </div>
                    ` : ''}
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button onclick="this.closest('.fixed').remove()" class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Auto remove after 5 seconds (longer for success messages)
    const autoRemoveTime = type === 'success' ? 5000 : 3000;
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 500);
        }
    }, autoRemoveTime);
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide-item');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');
    let currentSlide = 0;
    let autoSlideInterval;

    function showSlide(index) {
        // Hide all slides with modern animation
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0', 'scale-105');
                slide.classList.add('opacity-100', 'scale-100');
            } else {
                slide.classList.remove('opacity-100', 'scale-100');
                slide.classList.add('opacity-0', 'scale-105');
            }
        });

        // Update dots
        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.remove('bg-white/50');
                dot.classList.add('bg-secondary', 'shadow-lg');
            } else {
                dot.classList.remove('bg-secondary', 'shadow-lg');
                dot.classList.add('bg-white/50');
            }
        });

        currentSlide = index;
    }

    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }

    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 6000); // Auto advance every 6 seconds
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            stopAutoSlide();
            nextSlide();
            startAutoSlide();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            stopAutoSlide();
            prevSlide();
            startAutoSlide();
        });
    }

    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopAutoSlide();
            showSlide(index);
            startAutoSlide();
        });
    });

    // Pause auto-slide on hover
    const slider = document.getElementById('modernSlider');
    if (slider) {
        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', startAutoSlide);
    }

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            stopAutoSlide();
            prevSlide();
            startAutoSlide();
        } else if (e.key === 'ArrowRight') {
            stopAutoSlide();
            nextSlide();
            startAutoSlide();
        }
    });

    // Start auto-slide
    startAutoSlide();

    // Touch/swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    if (slider) {
        slider.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        slider.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });
    }

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            stopAutoSlide();
            if (diff > 0) {
                // Swipe left, show next slide
                nextSlide();
            } else {
                // Swipe right, show previous slide
                prevSlide();
            }
            startAutoSlide();
        }
    }
});
</script>

<style>
/* Fade-in animations for slider content */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-fade-in-delay-1 {
    animation: fadeIn 1s ease-out 0.3s both;
}

.animate-fade-in-delay-2 {
    animation: fadeIn 1s ease-out 0.6s both;
}

.animate-fade-in-delay-3 {
    animation: fadeIn 1s ease-out 0.9s both;
}

/* Smooth transitions */
.slider-item {
    transition: opacity 1s ease-in-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .animate-fade-in,
    .animate-fade-in-delay-1,
    .animate-fade-in-delay-2,
    .animate-fade-in-delay-3 {
        animation-duration: 0.8s;
    }
}
</style>

@endsection