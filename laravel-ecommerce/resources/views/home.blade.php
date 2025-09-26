@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <!-- Modern Creative Hero Slider -->
    <section class="relative py-8">
        <div class="container mx-auto px-4">
            <div id="modernSlider" class="relative h-[500px] rounded-3xl overflow-hidden shadow-2xl">
                @foreach($sliders as $index => $slide)
                    <div class="slide-item absolute inset-0 transition-all duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-105' }}"
                         data-slide="{{ $index }}">
                        <div class="relative w-full h-full">
                            <!-- Background Image -->
                            <img src="{{ $slide->image_path }}"
                                 alt="{{ $slide->getTranslation('title', app()->getLocale()) }}"
                                 class="w-full h-full object-cover">

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-transparent" 
                                 style="background-color: {{ $slide->background_color }}99;"></div>

                            <!-- Content -->
                            <div class="absolute inset-0 flex items-center">
                                <div class="container mx-auto px-8 relative z-10">
                                    <div class="max-w-2xl">
                                        <!-- Animated Content Card -->
                                        <div class="backdrop-blur-sm bg-white/10 rounded-2xl p-8 border border-white/20 shadow-2xl transform transition-all duration-700 hover:scale-105">
                                            <div class="space-y-6">
                                                <!-- Subtitle -->
                                                @if($slide->getTranslation('subtitle', app()->getLocale()))
                                                    <span class="inline-block px-4 py-2 text-sm font-medium rounded-full shadow-lg" 
                                                          style="background-color: rgba(var(--color-secondary-rgb), 0.9); color: var(--color-primary);">
                                                        {{ $slide->getTranslation('subtitle', app()->getLocale()) }}
                                                    </span>
                                                @endif

                                                <!-- Title -->
                                                <h1 class="text-4xl md:text-6xl font-roboto-black leading-tight"
                                                    style="color: {{ $slide->text_color ?? '#ffffff' }};">
                                                    {{ $slide->getTranslation('title', app()->getLocale()) }}
                                                </h1>

                                                <!-- Description -->
                                                @if($slide->getTranslation('description', app()->getLocale()))
                                                    <p class="text-lg md:text-xl font-roboto-light leading-relaxed"
                                                       style="color: {{ $slide->text_color ?? '#ffffff' }}aa;">
                                                        {{ $slide->getTranslation('description', app()->getLocale()) }}
                                                    </p>
                                                @endif

                                                <!-- Button -->
                                                @if($slide->button_text && $slide->button_url)
                                                    <div class="flex gap-4">
                                                        <a href="{{ $slide->button_url }}"
                                                           class="inline-flex items-center px-8 py-4 font-roboto-medium text-lg rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1" 
                                                           style="background-color: var(--color-secondary); color: var(--color-primary);"
                                                           onmouseover="this.style.backgroundColor='white'; this.style.color='var(--color-primary)';" 
                                                           onmouseout="this.style.backgroundColor='var(--color-secondary)'; this.style.color='var(--color-primary)';">
                                                            {{ $slide->button_text }}
                                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                            </svg>
                                                        </a>
                                                        <a href="{{ route('categories.index') }}"
                                                           class="inline-flex items-center px-8 py-4 border-2 font-roboto-medium text-lg rounded-xl transition-all duration-300 transform hover:-translate-y-1"
                                                           style="border-color: {{ $slide->text_color ?? '#ffffff' }}; color: {{ $slide->text_color ?? '#ffffff' }};"
                                                           onmouseover="this.style.backgroundColor='white'; this.style.color='var(--color-primary)';" 
                                                           onmouseout="this.style.backgroundColor='transparent'; this.style.color='{{ $slide->text_color ?? '#ffffff' }}';">
                                                            Categories
                                                        </a>
                                                    </div>
                                                @endif
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
                    @foreach($sliders as $index => $slide)
                        <button class="slider-dot w-4 h-4 rounded-full transition-all duration-300 {{ $index === 0 ? 'shadow-lg' : 'bg-white/50 hover:bg-white/70' }} hover:scale-125"
                                style="background-color: {{ $index === 0 ? 'var(--color-secondary)' : '' }};"
                                data-slide="{{ $index }}"></button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4" style="color: var(--color-primary);">@t('Categories')</h2>
                <p class="text-gray-600">@t('Explore our collections')</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}"
                       class="group bg-gray-50 rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300"
                       onmouseover="this.style.backgroundColor='var(--color-secondary)'; this.style.transform='translateY(-4px)';" 
                       onmouseout="this.style.backgroundColor='#f9fafb'; this.style.transform='translateY(0)';">
                        
                        <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center"
                             style="background-color: var(--color-primary);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        
                        <h3 class="font-medium text-gray-900 text-sm">{{ $category->name }}</h3>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4" style="color: var(--color-primary);">@t('Featured Products')</h2>
                <p class="text-gray-600">@t('Our most popular products')</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('products.index', ['featured' => 1]) }}"
                   class="inline-block px-8 py-3 rounded-lg font-medium transition-colors"
                   style="background-color: var(--color-secondary); color: var(--color-primary);"
                   onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='var(--color-secondary)';" 
                   onmouseout="this.style.backgroundColor='var(--color-secondary)'; this.style.color='var(--color-primary)';">
                    @t('View All Products')
                </a>
            </div>
        </div>
    </section>

    <!-- Recent Products -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4" style="color: var(--color-primary);">@t('New Arrivals')</h2>
                <p class="text-gray-600">@t('Latest products added to our collection')</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($recentProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('products.index', ['sort' => 'newest']) }}"
                   class="px-8 py-3 font-semibold rounded-lg transition-colors"
                   style="background-color: var(--color-secondary); color: var(--color-primary);"
                   onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='var(--color-secondary)';" 
                   onmouseout="this.style.backgroundColor='var(--color-secondary)'; this.style.color='var(--color-primary)';">
                    @t('View All New Products')
                </a>
            </div>
        </div>
    </section>

    <!-- Popular Products -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4" style="color: var(--color-primary);">@t('Best Sellers')</h2>
                <p class="text-gray-600">@t('Most popular products among our customers')</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($popularProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('products.index', ['sort' => 'popular']) }}"
                   class="px-8 py-3 font-semibold rounded-lg transition-colors"
                   style="background-color: var(--color-secondary); color: var(--color-primary);"
                   onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='var(--color-secondary)';" 
                   onmouseout="this.style.backgroundColor='var(--color-secondary)'; this.style.color='var(--color-primary)';">
                    @t('View All Best Sellers')
                </a>
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4" style="color: var(--color-primary);">@t('Our Brands')</h2>
                <p class="text-gray-600">@t('Trusted brands we partner with')</p>
            </div>
            
            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 max-w-4xl mx-auto">
                @foreach($brands as $brand)
                    <a href="{{ route('brands.show', $brand->slug) }}" 
                       class="group bg-gray-50 rounded-lg p-4 flex items-center justify-center hover:shadow-lg transition-all duration-300"
                       onmouseover="this.style.backgroundColor='var(--color-secondary)'; this.style.transform='translateY(-2px)';" 
                       onmouseout="this.style.backgroundColor='#f9fafb'; this.style.transform='translateY(0)';">
                        <div class="text-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2"
                                 style="background-color: var(--color-primary);">
                                <span class="text-sm font-bold text-white">{{ substr($brand->name, 0, 1) }}</span>
                            </div>
                            <h3 class="text-xs font-medium text-gray-900">{{ $brand->name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 text-white" style="background-color: var(--color-primary);">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold mb-4">@t('Newsletter')</h2>
            <p class="mb-8 opacity-90">@t('Subscribe for updates and exclusive offers')</p>

            <form class="max-w-md mx-auto flex gap-4">
                <input type="email"
                       placeholder="@t('Enter your email')"
                       class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-white focus:outline-none">
                <button type="submit"
                        class="px-6 py-3 rounded-lg font-semibold transition-colors"
                        style="background-color: var(--color-secondary); color: var(--color-primary);"
                        onmouseover="this.style.backgroundColor='white'; this.style.color='var(--color-primary)';" 
                        onmouseout="this.style.backgroundColor='var(--color-secondary)'; this.style.color='var(--color-primary)';">
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
                dot.classList.add('shadow-lg');
                dot.style.backgroundColor = 'var(--color-secondary)';
            } else {
                dot.classList.remove('shadow-lg');
                dot.classList.add('bg-white/50');
                dot.style.backgroundColor = '';
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