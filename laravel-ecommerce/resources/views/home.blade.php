@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <!-- Hero Slider Section -->
    @if($sliders && $sliders->count() > 0)
        <section class="relative overflow-hidden">
            <div id="heroSlider" class="relative w-full h-screen max-h-[600px]">
                @foreach($sliders as $index => $slider)
                    <div class="slider-item absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                         style="background-color: {{ $slider->background_color }};">
                        <div class="relative w-full h-full flex items-center">
                            @if($slider->image_path || $slider->image_url)
                                <div class="absolute inset-0">
                                    <img src="{{ $slider->image_path ?? $slider->image_url }}"
                                         alt="{{ $slider->title }}"
                                         class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/30"></div>
                                </div>
                            @endif

                            <div class="container mx-auto px-4 relative z-10">
                                <div class="max-w-4xl text-left {{ $loop->even ? 'ml-auto mr-0' : 'ml-0 mr-auto' }}">
                                    <div class="bg-black/20 backdrop-blur-sm rounded-2xl p-8 md:p-12 border border-white/10">
                                        @if($slider->title)
                                            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight animate-fade-in"
                                                style="color: {{ $slider->text_color }};">
                                                {{ $slider->title }}
                                            </h1>
                                        @endif

                                        @if($slider->subtitle)
                                            <p class="text-xl md:text-2xl mb-4 opacity-90 animate-fade-in-delay-1"
                                               style="color: {{ $slider->text_color }};">
                                                {{ $slider->subtitle }}
                                            </p>
                                        @endif

                                        @if($slider->description)
                                            <p class="text-lg mb-8 opacity-80 max-w-2xl animate-fade-in-delay-2"
                                               style="color: {{ $slider->text_color }};">
                                                {{ $slider->description }}
                                            </p>
                                        @endif

                                        @if($slider->button_text && $slider->button_url)
                                            <a href="{{ $slider->button_url }}"
                                               class="inline-flex items-center bg-secondary text-primary px-8 py-4 font-semibold text-lg hover:bg-secondary hover:scale-105 transition-all duration-300 rounded-lg shadow-xl border-2 border-secondary/30 animate-fade-in-delay-3">
                                                {{ $slider->button_text }}
                                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($sliders->count() > 1)
                    <!-- Navigation Arrows -->
                    <button id="prevSlide" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-secondary bg-opacity-80 hover:bg-secondary text-primary p-3 rounded-full transition-all duration-300 z-20 shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button id="nextSlide" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-secondary bg-opacity-80 hover:bg-secondary text-primary p-3 rounded-full transition-all duration-300 z-20 shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Dots Indicator -->
                    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
                        @foreach($sliders as $index => $slider)
                            <button class="slider-dot w-3 h-3 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-secondary' : 'bg-white bg-opacity-50' }}"
                                    data-slide="{{ $index }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @else
        <!-- Fallback Hero Section -->
        <section class="text-white py-20 bg-primary">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    @t('Welcome to Our Store')
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90">
                    @t('Discover amazing products at unbeatable prices')
                </p>
                <a href="{{ route('products.index') }}"
                   class="bg-white px-8 py-3 font-semibold hover:bg-gray-100 transition-colors text-primary" style="border-radius: 0;">
                    @t('Shop Now')
                </a>
            </div>
        </section>
    @endif

    <!-- Categories Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">@t('Shop by Category')</h2>
                <p class="text-gray-600">@t('Find what you\'re looking for')</p>
            </div>
            
            <div class="flex justify-center">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-{{ min(6, $categories->count()) }} gap-6 max-w-6xl">
                    @foreach($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}"
                           class="group bg-white p-6 text-center hover:shadow-xl transition-all duration-300 hover:bg-secondary hover:bg-opacity-10 border-2 border-gray-100 hover:border-secondary rounded-xl min-w-[140px]">
                            <div class="w-16 h-16 flex items-center justify-center mx-auto mb-4 transition-colors bg-gradient-to-br from-primary to-secondary rounded-xl shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-secondary transition-colors text-sm">{{ $category->name }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">@t('Featured Products')</h2>
                <p class="text-gray-600">@t('Hand-picked favorites just for you')</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('products.index', ['featured' => 1]) }}"
                   class="bg-secondary hover:bg-yellow-400 text-primary px-8 py-3 font-semibold rounded-lg transition-colors">
                    @t('View All Featured Products')
                </a>
            </div>
        </div>
    </section>

    <!-- Recent Products -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">@t('New Arrivals')</h2>
                <p class="text-gray-600">@t('Latest products added to our collection')</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
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
                <h2 class="text-3xl font-bold text-gray-900 mb-4">@t('Best Sellers')</h2>
                <p class="text-gray-600">@t('Most popular products among our customers')</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
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
                <h2 class="text-3xl font-bold text-gray-900 mb-4">@t('Featured Brands')</h2>
                <p class="text-gray-600">@t('Trusted brands we partner with')</p>
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

@if($sliders && $sliders->count() > 1)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slider-item');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');
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
@endsection