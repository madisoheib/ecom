@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
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
                                                <!-- Subtitle -->
                                                <span class="inline-block px-4 py-2 bg-secondary/90 text-black text-sm font-medium rounded-full shadow-lg">
                                                    {{ $slide['subtitle'] }}
                                                </span>

                                                <!-- Title -->
                                                <h1 class="text-4xl md:text-6xl font-roboto-black text-white leading-tight">
                                                    {{ $slide['title'] }}
                                                </h1>

                                                <!-- Description -->
                                                <p class="text-lg md:text-xl text-white/90 font-roboto-light leading-relaxed">
                                                    {{ $slide['description'] }}
                                                </p>

                                                <!-- Button -->
                                                <div class="flex gap-4">
                                                    <a href="{{ route('products.index') }}"
                                                       class="inline-flex items-center px-8 py-4 bg-secondary text-black font-roboto-medium text-lg rounded-xl hover:bg-white hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                                                        {{ $slide['button_text'] }}
                                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('categories.index') }}"
                                                       class="inline-flex items-center px-8 py-4 border-2 border-white text-white font-roboto-medium text-lg rounded-xl hover:bg-white hover:text-black transition-all duration-300 transform hover:-translate-y-1">
                                                        Categories
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

    <!-- Categories Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-roboto-bold text-black mb-4 tracking-wide">@t('Beauty Categories')</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-transparent via-secondary to-transparent mx-auto mb-6"></div>
                <p class="text-gray-600 font-roboto-light text-lg">@t('Explore our carefully curated skincare and beauty collections')</p>
            </div>
            
            <div class="flex justify-center">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-{{ min(6, $categories->count()) }} gap-6 max-w-6xl">
                    @foreach($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}"
                           class="group bg-white p-6 text-center hover:shadow-2xl transition-all duration-300 hover:bg-secondary/5 border-2 border-gray-100 hover:border-secondary rounded-xl min-w-[140px] transform hover:-translate-y-2">
                            <div class="w-16 h-16 flex items-center justify-center mx-auto mb-4 transition-colors bg-gradient-to-br from-primary to-secondary rounded-xl shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h3 class="font-roboto-medium text-gray-900 group-hover:text-black transition-colors text-sm">{{ $category->name }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-roboto-bold text-black mb-4 tracking-wide">@t('Featured Beauty Products')</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-transparent via-secondary to-transparent mx-auto mb-6"></div>
                <p class="text-gray-600 font-roboto-light text-lg">@t('Our most popular skincare and beauty essentials, loved by customers worldwide')</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('products.index', ['featured' => 1]) }}"
                   class="bg-black text-secondary border-2 border-secondary hover:bg-secondary hover:text-black px-10 py-4 font-roboto-medium rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    @t('Explore Our Complete Collection')
                </a>
            </div>
        </div>
    </section>

    <!-- Recent Products -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-roboto-bold text-gray-900 mb-4">@t('New Beauty Arrivals')</h2>
                <p class="text-gray-600 font-roboto-light">@t('Latest skincare and beauty products added to our collection')</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($recentProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('products.index', ['sort' => 'newest']) }}"
                   class="bg-secondary hover:bg-yellow-400 text-primary px-8 py-3 font-semibold rounded-lg transition-colors">
                    @t('View All New Products')
                </a>
            </div>
        </div>
    </section>

    <!-- Popular Products -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-roboto-bold text-gray-900 mb-4">@t('Beauty Best Sellers')</h2>
                <p class="text-gray-600 font-roboto-light">@t('Most popular beauty and skincare products among our customers')</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($popularProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('products.index', ['sort' => 'popular']) }}"
                   class="bg-secondary hover:bg-yellow-400 text-primary px-8 py-3 font-semibold rounded-lg transition-colors">
                    @t('View All Best Sellers')
                </a>
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-roboto-bold text-gray-900 mb-4">@t('Featured Beauty Brands')</h2>
                <p class="text-gray-600 font-roboto-light">@t('Trusted beauty and skincare brands we partner with')</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-8">
                @foreach($brands as $brand)
                    <a href="{{ route('brands.show', $brand->slug) }}" 
                       class="group bg-gray-50 rounded-lg p-6 flex items-center justify-center hover:shadow-lg transition-all duration-300 hover:bg-gray-100">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-2">
                                <span class="text-xl font-bold text-gray-600">{{ substr($brand->name, 0, 1) }}</span>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 group-hover:text-secondary transition-colors">{{ $brand->name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-primary text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">@t('Stay Updated')</h2>
            <p class="text-xl mb-8 opacity-90">@t('Subscribe to our newsletter for exclusive deals and updates')</p>

            <form class="max-w-md mx-auto flex gap-4">
                <input type="email"
                       placeholder="@t('Enter your email')"
                       class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-white focus:outline-none">
                <button type="submit"
                        class="bg-white text-primary px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    @t('Subscribe')
                </button>
            </form>
        </div>
    </section>
</div>

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
@endsection