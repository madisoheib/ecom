@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <!-- Simplified Hero Slider -->
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

                            <!-- Simple Content - Just Title -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    @if(app()->getLocale() === 'ar')
                                        <h1 class="text-4xl md:text-6xl leading-tight text-white" style="font-family: 'Amiri', 'Tajawal', serif !important; font-weight: 600; direction: rtl;">
                                            {{ $slide->getTranslation('title', app()->getLocale()) }}
                                        </h1>
                                    @else
                                        <h1 class="text-4xl md:text-6xl leading-tight text-white" style="font-family: 'Dancing Script', cursive !important; font-weight: 400;">
                                            {{ $slide->getTranslation('title', app()->getLocale()) }}
                                        </h1>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Navigation Arrows -->
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

                <!-- Dots Indicator -->
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
                @if(app()->getLocale() === 'ar')
                    <h2 class="mb-4" style="font-family: 'Amiri', 'Tajawal', serif !important; font-size: 42px; line-height: 48px; color: #505e5b; text-align: center; font-weight: 600; direction: rtl;">@t('Categories')</h2>
                @else  
                    <h2 class="mb-4" style="font-family: 'Dancing Script', cursive !important; font-size: 38px; line-height: 44px; color: #505e5b; text-align: center; font-weight: 400; text-transform: capitalize;">@t('Categories')</h2>
                @endif
                <p class="text-gray-600">@t('Explore our collections')</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
                @php
                    $beautyIcons = [
                        // Skincare
                        '<svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>',
                        
                        // Makeup/Lipstick
                        '<svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18.5 3c-1.4 0-2.5 1.1-2.5 2.5 0 .8.4 1.5 1 2l-7 7c-.5-.6-1.2-1-2-1-1.4 0-2.5 1.1-2.5 2.5S6.6 18.5 8 18.5s2.5-1.1 2.5-2.5c0-.8-.4-1.5-1-2l7-7c.5.6 1.2 1 2 1 1.4 0 2.5-1.1 2.5-2.5S19.9 3 18.5 3z"/><circle cx="8" cy="16" r="1.5"/><circle cx="18.5" cy="5.5" r="1.5"/></svg>',
                        
                        // Perfume
                        '<svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M9 2v4.5c0 .83.67 1.5 1.5 1.5h3c.83 0 1.5-.67 1.5-1.5V2H9zm6 6H9c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z"/><circle cx="12" cy="16" r="2"/></svg>',
                        
                        // Face care
                        '<svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.14 2 5 5.14 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.86-3.14-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>',
                        
                        // Hair care
                        '<svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>',
                        
                        // Nail care
                        '<svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>',
                        
                        // Body care
                        '<svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>',
                        
                        // Beauty accessories
                        '<svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>'
                    ];
                @endphp
                @foreach($categories as $index => $category)
                    <a href="{{ route('categories.show', $category->slug) }}"
                       class="group bg-gray-50 rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center"
                       onmouseover="this.style.backgroundColor='var(--color-secondary)'; this.style.transform='translateY(-4px)';" 
                       onmouseout="this.style.backgroundColor='#f9fafb'; this.style.transform='translateY(0)';">
                        
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center shadow-lg"
                             style="background: linear-gradient(135deg, var(--color-primary), #333333);">
                            {!! $beautyIcons[$index % count($beautyIcons)] !!}
                        </div>
                        
                        <h3 class="font-medium text-gray-900 text-sm text-center leading-tight">{{ $category->name }}</h3>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                @if(app()->getLocale() === 'ar')
                    <h2 class="mb-4" style="font-family: 'Amiri', 'Tajawal', serif !important; font-size: 42px; line-height: 48px; color: #505e5b; text-align: center; font-weight: 600; direction: rtl;">@t('Featured Products')</h2>
                @else  
                    <h2 class="mb-4" style="font-family: 'Dancing Script', cursive !important; font-size: 38px; line-height: 44px; color: #505e5b; text-align: center; font-weight: 400; text-transform: capitalize;">@t('Featured Products')</h2>
                @endif
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
                @if(app()->getLocale() === 'ar')
                    <h2 class="mb-4" style="font-family: 'Amiri', 'Tajawal', serif !important; font-size: 42px; line-height: 48px; color: #505e5b; text-align: center; font-weight: 600; direction: rtl;">@t('New Arrivals')</h2>
                @else  
                    <h2 class="mb-4" style="font-family: 'Dancing Script', cursive !important; font-size: 38px; line-height: 44px; color: #505e5b; text-align: center; font-weight: 400; text-transform: capitalize;">@t('New Arrivals')</h2>
                @endif
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
                @if(app()->getLocale() === 'ar')
                    <h2 class="mb-4" style="font-family: 'Amiri', 'Tajawal', serif !important; font-size: 42px; line-height: 48px; color: #505e5b; text-align: center; font-weight: 600; direction: rtl;">@t('Best Sellers')</h2>
                @else  
                    <h2 class="mb-4" style="font-family: 'Dancing Script', cursive !important; font-size: 38px; line-height: 44px; color: #505e5b; text-align: center; font-weight: 400; text-transform: capitalize;">@t('Best Sellers')</h2>
                @endif
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
                @if(app()->getLocale() === 'ar')
                    <h2 class="mb-4" style="font-family: 'Amiri', 'Tajawal', serif !important; font-size: 42px; line-height: 48px; color: #505e5b; text-align: center; font-weight: 600; direction: rtl;">@t('Our Brands')</h2>
                @else  
                    <h2 class="mb-4" style="font-family: 'Dancing Script', cursive !important; font-size: 38px; line-height: 44px; color: #505e5b; text-align: center; font-weight: 400; text-transform: capitalize;">@t('Our Brands')</h2>
                @endif
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
            @if(app()->getLocale() === 'ar')
                <h2 class="mb-4" style="font-family: 'Amiri', 'Tajawal', serif !important; font-size: 42px; line-height: 48px; color: #505e5b; text-align: center; font-weight: 600; direction: rtl;">@t('Newsletter')</h2>
            @else  
                <h2 class="mb-4" style="font-family: 'Dancing Script', cursive !important; font-size: 38px; line-height: 44px; color: #505e5b; text-align: center; font-weight: 400; text-transform: capitalize;">@t('Newsletter')</h2>
            @endif
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

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide-item');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');
    let currentSlide = 0;
    let autoSlideInterval;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0', 'scale-105');
                slide.classList.add('opacity-100', 'scale-100');
            } else {
                slide.classList.remove('opacity-100', 'scale-100');
                slide.classList.add('opacity-0', 'scale-105');
            }
        });

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
        autoSlideInterval = setInterval(nextSlide, 6000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

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

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopAutoSlide();
            showSlide(index);
            startAutoSlide();
        });
    });

    const slider = document.getElementById('modernSlider');
    if (slider) {
        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', startAutoSlide);
    }

    startAutoSlide();
});
</script>