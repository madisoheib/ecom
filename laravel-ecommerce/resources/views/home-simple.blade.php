@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Hero Slider Section -->
    @if($sliders && $sliders->count() > 0)
        <section class="relative overflow-hidden">
            <div id="heroSlider" class="relative w-full h-[400px] sm:h-[500px] md:h-screen md:max-h-[600px]">
                @foreach($sliders as $index => $slider)
                    <div class="slider-item absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                         style="background: linear-gradient(135deg, #f5f5f5 0%, #ffffff 100%);">
                        <div class="relative w-full h-full flex items-center">
                            @if($slider->image_path || $slider->image_url)
                                <div class="absolute inset-0">
                                    <img src="{{ $slider->image_path ?? $slider->image_url }}"
                                         alt="{{ $slider->title }}"
                                         class="w-full h-full object-cover sm:object-center brightness-110 contrast-95">
                                    <div class="absolute inset-0 bg-gradient-to-r from-white/60 sm:from-white/40 to-white/20 sm:to-white/10"></div>
                                </div>
                            @endif

                            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                                <div class="max-w-4xl mx-auto sm:mx-0 {{ $loop->even ? 'sm:ml-auto sm:mr-0' : 'sm:ml-0 sm:mr-auto' }}">
                                    <div class="bg-white/95 backdrop-blur-md rounded-2xl sm:rounded-3xl p-6 sm:p-8 md:p-12 shadow-xl sm:shadow-2xl">
                                        @if($slider->title)
                                            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-6xl font-display mb-4 sm:mb-6 leading-tight animate-fade-in text-gray-900">
                                                {{ $slider->title }}
                                            </h1>
                                        @endif

                                        @if($slider->subtitle)
                                            <p class="text-base sm:text-lg md:text-xl lg:text-2xl mb-3 sm:mb-4 text-gray-700 animate-fade-in-delay-1">
                                                {{ $slider->subtitle }}
                                            </p>
                                        @endif

                                        @if($slider->description)
                                            <p class="text-sm sm:text-base md:text-lg mb-6 sm:mb-8 text-gray-600 max-w-2xl animate-fade-in-delay-2">
                                                {{ $slider->description }}
                                            </p>
                                        @endif

                                        @if($slider->button_text && $slider->button_url)
                                            <a href="{{ $slider->button_url }}"
                                               class="inline-block bg-primary text-white px-6 sm:px-8 md:px-10 py-3 sm:py-4 font-light text-sm sm:text-base hover:bg-primary-700 transition-all duration-300 rounded-full animate-fade-in-delay-3">
                                                {{ $slider->button_text }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($sliders->count() > 1)
                    <!-- Minimal Dots Indicator -->
                    <div class="absolute bottom-4 sm:bottom-6 md:bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2 sm:space-x-3 md:space-x-4 z-20">
                        @foreach($sliders as $index => $slider)
                            <button class="slider-dot w-2 h-2 rounded-full transition-all duration-500 {{ $index === 0 ? 'bg-white w-6 sm:w-8' : 'bg-white/50' }}"
                                    data-slide="{{ $index }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <!-- Water-like curved bottom transition -->
        <div class="relative w-full -mt-1 overflow-hidden">
            <svg class="w-full h-16 sm:h-24 md:h-32" preserveAspectRatio="none" viewBox="0 0 1200 150" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="sliderGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#f5f5f5;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#ffffff;stop-opacity:1" />
                    </linearGradient>
                </defs>
                <path fill="url(#sliderGradient)" d="M0,0 L0,80 Q200,120 400,80 T800,80 Q1000,120 1200,80 L1200,0 Z">
                    <animate attributeName="d"
                             values="M0,0 L0,80 Q200,120 400,80 T800,80 Q1000,120 1200,80 L1200,0 Z;
                                     M0,0 L0,100 Q200,60 400,100 T800,100 Q1000,60 1200,100 L1200,0 Z;
                                     M0,0 L0,80 Q200,120 400,80 T800,80 Q1000,120 1200,80 L1200,0 Z"
                             dur="8s"
                             repeatCount="indefinite"/>
                </path>
            </svg>
        </div>
    @else
        <!-- Fallback Hero Section -->
        <section class="text-white py-20 bg-primary">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-6xl font-display mb-6">
                    @t('Welcome to Our Store')
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90">
                    @t('Discover amazing products at unbeatable prices')
                </p>
                <a href="{{ route('products.index') }}"
                   class="bg-secondary hover:bg-secondary text-primary px-8 py-3 font-subheading rounded-lg transition-colors">
                    @t('Shop Now')
                </a>
            </div>
        </section>
    @endif

    <!-- Categories Grid - Enhanced Visibility -->
    @if($categories && $categories->count() > 0)
    <section class="py-12 sm:py-16 md:py-20 lg:py-24 bg-gradient-to-br from-gray-50 to-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-heading text-gray-900 text-center mb-2 sm:mb-4">@t('Shop by Category')</h2>
            <p class="text-sm sm:text-base md:text-lg text-gray-600 text-center mb-8 sm:mb-12 md:mb-16">@t('Discover our exclusive fragrance collections')</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6 md:gap-8 max-w-7xl mx-auto">
                @foreach($categories as $index => $category)
                <a href="{{ route('categories.show', $category->slug) }}"
                   class="group relative p-4 sm:p-6 md:p-8 bg-white border sm:border-2 border-gray-100 rounded-2xl sm:rounded-3xl hover:border-primary hover:shadow-xl sm:hover:shadow-2xl transition-all duration-300 text-center transform hover:-translate-y-1 sm:hover:-translate-y-2 hover:scale-105">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 md:w-20 md:h-20 bg-gradient-to-br from-primary to-secondary rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 md:mb-6 group-hover:rotate-6 transition-all duration-300 shadow-lg sm:shadow-xl">
                        @php
                            $iconClasses = 'w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 text-white';
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
                                <circle cx="12" cy="12" r="4" fill="rgba(255,255,255,0.3)"/>
                            </svg>
                        @endif
                        </div>
                        <h3 class="text-sm sm:text-base md:text-lg font-heading text-gray-900 group-hover:text-primary transition-colors duration-300">
                            {{ $category->name }}
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-2 group-hover:text-gray-700 transition-colors">
                            @t('Explore Collection')
                        </p>
                    </a>
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
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 justify-items-center">
                    @foreach($featuredProducts as $product)
                        @include('partials.product-card-modern', ['product' => $product])
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
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 justify-items-center">
                    @foreach($recentProducts->take(8) as $product)
                        @include('partials.product-card-modern', ['product' => $product])
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

@if($sliders && $sliders->count() > 1)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slider-item');
    const dots = document.querySelectorAll('.slider-dot');
    let currentSlide = 0;
    let autoSlideInterval;

    function showSlide(index) {
        // Hide all slides
        slides.forEach(slide => {
            slide.style.opacity = '0';
        });

        // Update dots
        dots.forEach(dot => {
            dot.classList.remove('bg-secondary');
            dot.classList.add('bg-white', 'bg-opacity-50');
        });

        // Show current slide
        if (slides[index]) {
            slides[index].style.opacity = '1';
        }

        // Highlight current dot
        if (dots[index]) {
            dots[index].classList.remove('bg-white', 'bg-opacity-50');
            dots[index].classList.add('bg-secondary');
        }

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
        autoSlideInterval = setInterval(nextSlide, 5000); // Auto advance every 5 seconds
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    // Removed arrow button listeners for cleaner interface

    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopAutoSlide();
            showSlide(index);
            startAutoSlide();
        });
    });

    // Pause auto-slide on hover
    const slider = document.getElementById('heroSlider');
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
        });

        slider.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
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
@endif

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