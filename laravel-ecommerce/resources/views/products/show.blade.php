@extends('layouts.app')

@section('content')
<!-- Hero Product Section -->
<div class="bg-gradient-to-br from-primary via-primary-600 to-primary-800 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-48 translate-x-48"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-32 -translate-x-32"></div>

    <div class="container mx-auto px-6 py-8 relative z-10">
        <!-- Breadcrumbs -->
        @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-white opacity-80">
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        @if($loop->last)
                            <li class="text-white font-subheading">{{ $breadcrumb['name'] }}</li>
                        @else
                            <li>
                                <a href="{{ $breadcrumb['url'] }}" class="hover:text-white opacity-70 hover:opacity-100 transition-opacity">{{ $breadcrumb['name'] }}</a>
                            </li>
                            <li><svg class="w-4 h-4 text-white opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg></li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        @endif

        <!-- Hero Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Product Images -->
            <div class="order-2 lg:order-1">
                <div class="bg-white bg-opacity-10 backdrop-blur-sm p-8 rounded-3xl border border-white border-opacity-20">
                    @php
                        $imageUrl = 'https://picsum.photos/600/600?random=' . $product->id;
                    @endphp
                    <div class="relative overflow-hidden rounded-2xl group">
                        <img src="{{ $imageUrl }}"
                             alt="{{ $product->name }}"
                             class="w-full h-96 object-cover transition-all duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-50 transition-opacity duration-300"></div>

                        <!-- Quick View Badge -->
                        <div class="absolute top-4 left-4 bg-white bg-opacity-90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-subheading text-primary flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            @t('Premium Quality')
                        </div>

                        @if($product->stock_quantity <= 5 && $product->stock_quantity > 0)
                        <!-- Low Stock Warning -->
                        <div class="absolute top-4 right-4 bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-heading animate-pulse">
                            Only {{ $product->stock_quantity }} left!
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Hero Product Info -->
            <div class="order-1 lg:order-2 space-y-6">
                <!-- Brand -->
                @if($product->brand)
                    <div class="mb-4">
                        <a href="{{ route('brands.show', $product->brand->slug) }}"
                           class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm text-white hover:bg-opacity-30 font-subheading transition-all duration-200 rounded-full border border-white border-opacity-30">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            {{ $product->brand->name }}
                        </a>
                    </div>
                @endif

                <!-- Product Name -->
                <h1 class="text-4xl lg:text-5xl font-heading text-white mb-4 leading-tight">
                    {{ $product->name }}
                </h1>

                <!-- Product Tagline -->
                <p class="text-lg text-white opacity-80 mb-6 font-light">
                    @t('Premium quality product with exceptional value')
                </p>

                <!-- Price -->
                <div class="mb-8">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <div class="space-y-2">
                            <div class="flex items-baseline space-x-3">
                                <span class="text-4xl font-subheading text-white">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="text-xl text-white opacity-60 line-through font-light">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="bg-red-500 text-white px-3 py-1 text-sm font-subheading rounded-full">
                                    @php
                                        $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                    @endphp
                                    Save {{ $discount }}%
                                </span>
                                <span class="text-white opacity-70 text-sm font-light">
                                    You save ${{ number_format($product->price - $product->sale_price, 2) }}
                                </span>
                            </div>
                        </div>
                    @else
                        <span class="text-4xl font-subheading text-white">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Stock Status & Urgency -->
                <div class="mb-8">
                    @if($product->stock_quantity > 0)
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4 border border-white border-opacity-30">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-400 rounded-full mr-3 animate-pulse"></div>
                                    <span class="text-white font-subheading">@t('In Stock')</span>
                                </div>
                                @if($product->stock_quantity <= 10)
                                    <span class="text-orange-300 text-sm font-light">
                                        Only {{ $product->stock_quantity }} left
                                    </span>
                                @endif
                            </div>
                            @if($product->stock_quantity <= 10)
                                <div class="w-full bg-white bg-opacity-20 rounded-full h-2 mb-2">
                                    <div class="bg-orange-400 h-2 rounded-full" style="width: {{ min(100, ($product->stock_quantity / 10) * 100) }}%"></div>
                                </div>
                                <p class="text-white opacity-70 text-sm font-light">High demand - Limited stock remaining</p>
                            @endif
                        </div>
                    @else
                        <div class="bg-red-500 bg-opacity-20 backdrop-blur-sm rounded-xl p-4 border border-red-500 border-opacity-30">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-400 rounded-full mr-3"></div>
                                <span class="text-white font-subheading">@t('Out of Stock')</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Key Features/Benefits -->
                <div class="mb-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-4 border border-white border-opacity-20">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-white font-subheading">@t('Premium Quality')</span>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-4 border border-white border-opacity-20">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-white font-subheading">@t('Fast Shipping')</span>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-4 border border-white border-opacity-20">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-white font-subheading">@t('Satisfaction Guaranteed')</span>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-4 border border-white border-opacity-20">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                </svg>
                                <span class="text-white font-subheading">@t('Best Value')</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                @if($product->stock_quantity > 0)
                    <div class="space-y-4">
                        <button onclick="scrollToOrder()"
                                class="w-full bg-white text-primary px-8 py-4 font-subheading text-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl rounded-2xl">
                            <span class="flex items-center justify-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                @t('Order Now')
                            </span>
                        </button>

                        <div class="flex space-x-3">
                            <button onclick="addToCart({{ $product->id }})"
                                    class="flex-1 bg-white bg-opacity-20 backdrop-blur-sm text-white border-2 border-white border-opacity-30 px-6 py-3 font-subheading transition-all duration-200 hover:bg-opacity-30 rounded-xl">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                                </svg>
                                @t('Add to Cart')
                            </button>
                            <button class="bg-white bg-opacity-20 backdrop-blur-sm text-white border-2 border-white border-opacity-30 px-6 py-3 transition-all duration-200 hover:bg-opacity-30 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-6 py-12">

        <!-- Value Proposition Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-heading text-gray-900 mb-4">@t('Why Choose This Product?')</h2>
                    <p class="text-xl text-gray-600">@t('Exceptional quality meets unbeatable value')</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-subheading text-gray-900 mb-2">@t('Lightning Fast Delivery')</h3>
                        <p class="text-gray-600">@t('Get your order delivered within 24-48 hours in most areas')</p>
                    </div>

                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-subheading text-gray-900 mb-2">@t('100% Satisfaction Guarantee')</h3>
                        <p class="text-gray-600">@t('Not happy? Return within 30 days for a full refund')</p>
                    </div>

                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-subheading text-gray-900 mb-2">@t('Premium Quality')</h3>
                        <p class="text-gray-600">@t('Handpicked products that meet our strict quality standards')</p>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="bg-gray-50 rounded-3xl p-8 mb-16">
                    <h3 class="text-3xl font-heading text-gray-900 mb-8 text-center">@t('Product Details')</h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @if($product->description)
                            <div class="bg-white rounded-2xl p-6">
                                <h4 class="text-xl font-subheading text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @t('Description')
                                </h4>
                                <div class="text-gray-700 leading-relaxed">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>
                        @endif

                        <div class="space-y-6">
                            @if($product->sku)
                                <div class="bg-white rounded-2xl p-6">
                                    <h4 class="text-xl font-subheading text-gray-900 mb-4 flex items-center">
                                        <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                        @t('Product Code')
                                    </h4>
                                    <p class="text-gray-700 font-mono bg-gray-100 px-4 py-3 rounded-lg inline-block">{{ $product->sku }}</p>
                                </div>
                            @endif

                            @if($product->categories && $product->categories->count() > 0)
                                <div class="bg-white rounded-2xl p-6">
                                    <h4 class="text-xl font-subheading text-gray-900 mb-4 flex items-center">
                                        <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        @t('Categories')
                                    </h4>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($product->categories as $category)
                                            <a href="{{ route('categories.show', $category->slug) }}"
                                               class="inline-flex items-center px-4 py-2 bg-primary-50 text-primary hover:bg-primary-100 transition-colors duration-200 font-subheading rounded-full">
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($product->specifications)
                                <div class="bg-white rounded-2xl p-6">
                                    <h4 class="text-xl font-subheading text-gray-900 mb-4 flex items-center">
                                        <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        @t('Specifications')
                                    </h4>
                                    <div class="text-gray-700 leading-relaxed">
                                        {!! nl2br(e($product->specifications)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Order Section with Urgency -->
        <section id="orderSection" class="py-16 bg-gradient-to-br from-primary-50 via-white to-secondary-50">
            <div class="container mx-auto px-6">
                <!-- Urgency Banner -->
                <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-2xl p-6 mb-8 text-center">
                    <div class="flex items-center justify-center mb-2">
                        <svg class="w-6 h-6 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-xl font-heading">⚡ {{ __('LIMITED TIME OFFER') }} ⚡</span>
                    </div>
                    <p class="text-lg opacity-90">@t('Order within the next') <span id="countdown" class="font-heading">23:59:45</span> @t('to secure this price!')</p>
                </div>

                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-primary to-primary-700 text-white p-8">
                        <div class="text-center">
                            <h3 class="text-3xl font-subheading mb-2">@t('Quick Order - No Registration Required')</h3>
                            <p class="text-lg opacity-80 font-light">@t('Get yours delivered in 24-48 hours!')</p>
                            <div class="flex items-center justify-center mt-4 space-x-6">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <span class="font-subheading">@t('100% Secure')</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span class="font-subheading">@t('Fast Delivery')</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-subheading">@t('Money Back Guarantee')</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">

                    <form id="guestOrderForm" method="POST" action="{{ route('guest-order.store') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Full Name -->
                            <div>
                                <label for="full_name" class="block text-sm font-subheading text-gray-700 mb-2">{{ __('Full Name') }} *</label>
                                <input type="text" id="full_name" name="full_name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="phone_number" class="block text-sm font-subheading text-gray-700 mb-2">{{ __('Phone Number') }} *</label>
                                <input type="tel" id="phone_number" name="phone_number" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                            </div>

                            <!-- Email (Optional) -->
                            <div>
                                <label for="email" class="block text-sm font-subheading text-gray-700 mb-2">{{ __('Email') }} ({{ __('Optional') }})</label>
                                <input type="email" id="email" name="email"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                       placeholder="{{ __('For order updates and promotions') }}">
                                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                            </div>

                            <!-- Region -->
                            <div>
                                <label for="region_id" class="block text-sm font-subheading text-gray-700 mb-2">{{ __('Region') }}</label>
                                <select id="region_id" name="region_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">{{ __('Select Region') }}</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-subheading text-gray-700 mb-2">{{ __('Quantity') }} *</label>
                                <div class="flex items-center">
                                    <button type="button" onclick="decreaseQuantity()"
                                            class="px-3 py-2 border border-gray-300 rounded-l-lg hover:bg-gray-50">-</button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->quantity ?? 100 }}" required
                                           class="w-20 px-3 py-2 border-t border-b border-gray-300 text-center focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <button type="button" onclick="increaseQuantity()"
                                            class="px-3 py-2 border border-gray-300 rounded-r-lg hover:bg-gray-50">+</button>
                                    <span class="ml-4 text-sm text-gray-600">
                                        {{ __('Price') }}: $<span id="totalPrice">{{ number_format($product->price, 2) }}</span>
                                    </span>
                                </div>
                                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-6">
                            <label for="address" class="block text-sm font-subheading text-gray-700 mb-2">{{ __('Delivery Address') }} *</label>
                            <textarea id="address" name="address" rows="3" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                      placeholder="{{ __('Street address, city, postal code') }}"></textarea>
                            <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-subheading text-gray-700 mb-2">{{ __('Additional Notes') }} ({{ __('Optional') }})</label>
                            <textarea id="notes" name="notes" rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                      placeholder="{{ __('Special instructions, preferred delivery time, etc.') }}"></textarea>
                            <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Captcha -->
                        <div class="mb-6">
                            <label class="block text-sm font-subheading text-gray-700 mb-2">{{ __('Security Verification') }} *</label>
                            <div class="flex items-center space-x-4">
                                <div id="captcha-container">
                                    {!! captcha_img('flat') !!}
                                </div>
                                <button type="button" onclick="refreshCaptcha()"
                                        class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </div>
                            <input type="text" name="captcha" required
                                   class="mt-2 w-40 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="{{ __('Enter captcha') }}">
                            <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Trust Signals -->
                        <div class="bg-gray-50 rounded-2xl p-6 mb-8">
                            <div class="text-center mb-4">
                                <h4 class="text-lg font-subheading text-gray-900 mb-2">🎯 @t('Join 50,000+ Happy Customers')</h4>
                                <div class="flex items-center justify-center space-x-1 mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm font-subheading text-gray-600">(4.9/5 {{ __('from') }} 12,847 {{ __('reviews') }})</span>
                                </div>
                                <div class="grid grid-cols-3 gap-4 text-center">
                                    <div>
                                        <div class="text-2xl font-heading text-primary">99.2%</div>
                                        <div class="text-xs text-gray-600">@t('Satisfaction Rate')</div>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-heading text-green-600">24H</div>
                                        <div class="text-xs text-gray-600">@t('Fast Delivery')</div>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-heading text-blue-600">30D</div>
                                        <div class="text-xs text-gray-600">@t('Money Back')</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="space-y-4">
                            <button type="submit" id="submitOrderBtn"
                                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-5 font-subheading text-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl rounded-2xl">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    @t('Order Now') - $<span id="submitPrice">{{ number_format($product->price, 2) }}</span>
                                </span>
                            </button>

                            <div class="text-center text-sm text-gray-500">
                                ✅ @t('Instant confirmation') • 📱 @t('SMS tracking') • 🚚 @t('Fast delivery')
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-subheading text-blue-900 mb-1">💡 @t('Want exclusive discounts?')</p>
                                        <a href="{{ route('register') }}"
                                           class="text-sm text-blue-700 hover:text-blue-800 underline font-subheading">
                                            @t('Create an account for 10% off your next order + VIP perks!')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
            </div>
        </div>

        <!-- Customer Reviews Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-heading text-gray-900 mb-4">⭐ @t('What Our Customers Say')</h2>
                    <p class="text-xl text-gray-600">@t('Real reviews from verified buyers')</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <div class="flex items-center mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-gray-700 mb-4">"@t('Excellent quality and fast delivery! Exactly as described and works perfectly. Highly recommend!')"</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-subheading mr-3">SJ</div>
                            <div>
                                <p class="font-subheading text-gray-900">Sarah Johnson</p>
                                <p class="text-sm text-gray-500">@t('Verified Buyer')</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-6">
                        <div class="flex items-center mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-gray-700 mb-4">"@t('Best purchase I\'ve made this year! Customer service was amazing and the product exceeded expectations.')"</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-subheading mr-3">MR</div>
                            <div>
                                <p class="font-subheading text-gray-900">Mike Rodriguez</p>
                                <p class="text-sm text-gray-500">@t('Verified Buyer')</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-6">
                        <div class="flex items-center mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-gray-700 mb-4">"@t('Super fast shipping and great packaging. The quality is outstanding for the price. Will definitely order again!')"</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-subheading mr-3">EL</div>
                            <div>
                                <p class="font-subheading text-gray-900">Emily Lee</p>
                                <p class="text-sm text-gray-500">@t('Verified Buyer')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Products -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <section class="py-16 bg-gray-50">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-heading text-gray-900 mb-4">🔥 @t('You Might Also Like')</h2>
                        <p class="text-xl text-gray-600">@t('Hand-picked recommendations just for you')</p>
                        <div class="w-24 h-1 bg-gradient-primary mx-auto mt-4 rounded-full"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($relatedProducts as $relatedProduct)
                            @include('partials.product-card', ['product' => $relatedProduct])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </div>
</div>

<!-- Schema.org markup for SEO -->
@if(isset($productSchema))
    <script type="application/ld+json">
        @if(is_string($productSchema))
            {!! $productSchema !!}
        @else
            {!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        @endif
    </script>
@endif

@if(isset($breadcrumbSchema))
    <script type="application/ld+json">
        @if(is_string($breadcrumbSchema))
            {!! $breadcrumbSchema !!}
        @else
            {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        @endif
    </script>
@endif

<script>
const productPrice = {{ $product->price }};

// Smooth scroll to order section
function scrollToOrder() {
    document.getElementById('orderSection').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

// Countdown timer
function initCountdown() {
    const countdownElement = document.getElementById('countdown');
    if (!countdownElement) return;

    let timeLeft = 24 * 60 * 60; // 24 hours in seconds

    function updateCountdown() {
        const hours = Math.floor(timeLeft / 3600);
        const minutes = Math.floor((timeLeft % 3600) / 60);
        const seconds = timeLeft % 60;

        countdownElement.textContent =
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft > 0) {
            timeLeft--;
        } else {
            timeLeft = 24 * 60 * 60; // Reset to 24 hours
        }
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
}

// Initialize countdown when page loads
document.addEventListener('DOMContentLoaded', initCountdown);

function updatePrice() {
    const quantity = document.getElementById('quantity').value;
    const totalPrice = (productPrice * quantity).toFixed(2);
    document.getElementById('totalPrice').textContent = totalPrice;
    document.getElementById('submitPrice').textContent = totalPrice;
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const max = parseInt(quantityInput.getAttribute('max'));
    const current = parseInt(quantityInput.value);
    if (current < max) {
        quantityInput.value = current + 1;
        updatePrice();
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const min = parseInt(quantityInput.getAttribute('min'));
    const current = parseInt(quantityInput.value);
    if (current > min) {
        quantityInput.value = current - 1;
        updatePrice();
    }
}

// Update price when quantity changes manually
document.getElementById('quantity').addEventListener('input', updatePrice);

function refreshCaptcha() {
    fetch('{{ route("refresh.captcha") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('captcha-container').innerHTML = data.captcha;
        })
        .catch(error => {
            console.error('Error refreshing captcha:', error);
        });
}

// Handle form submission
document.getElementById('guestOrderForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = document.getElementById('submitOrderBtn');
    const originalText = submitBtn.innerHTML;

    // Clear previous errors
    document.querySelectorAll('.error-message').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });

    // Show loading state
    submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 inline mr-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>{{ __('Processing...') }}';
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
            // Show enhanced success message
            const successModal = `
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-3xl p-8 max-w-md w-full text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-heading text-gray-900 mb-2">🎉 {{ __('Order Confirmed!') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('Your order') }} #${data.order_number} {{ __('has been placed successfully!') }}</p>
                        <p class="text-sm text-gray-500 mb-6">{{ __('You\'ll receive SMS updates about your delivery.') }}</p>
                        <button onclick="this.parentElement.parentElement.remove()"
                                class="bg-primary text-white px-6 py-3 rounded-xl font-subheading hover:bg-primary-700 transition-colors">
                            {{ __('Continue Shopping') }}
                        </button>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', successModal);

            // Reset form
            this.reset();
            updatePrice();
            refreshCaptcha();
        } else {
            // Handle validation errors
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const errorEl = document.querySelector(`[name="${field}"]`).parentElement.querySelector('.error-message');
                    if (errorEl) {
                        errorEl.textContent = data.errors[field][0];
                        errorEl.classList.remove('hidden');
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __('An error occurred. Please try again.') }}');
        refreshCaptcha();
    })
    .finally(() => {
        // Restore button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>
@endsection