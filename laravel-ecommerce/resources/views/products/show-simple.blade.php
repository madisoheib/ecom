@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-4 py-12">
        <!-- Simple Centered Product Display -->
        <div class="text-center space-y-8">
            <!-- Product Image -->
            <div class="relative mx-auto w-full max-w-md">
                @php
                    // Real perfume images array
                    $perfumeImages = [
                        'https://images.unsplash.com/photo-1541643600914-78b084683601?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1594736797933-d0301ba2fe65?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1528740561666-dc2479dc08ab?w=500&h=500&fit=crop',
                        'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=500&h=500&fit=crop'
                    ];
                    $imageUrl = $perfumeImages[($product->id - 1) % count($perfumeImages)];
                @endphp
                <img src="{{ $imageUrl }}"
                     alt="{{ $product->name }}"
                     class="w-full h-96 object-cover rounded-2xl shadow-sm">

                @if($countrySpecificStock <= 5 && $countrySpecificStock > 0)
                <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-xs font-subheading text-orange-600 shadow-sm">
                    {{ __('Only') }} {{ $countrySpecificStock }} {{ __('left') }}
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-4">
                <!-- Title -->
                <h1 class="text-3xl font-heading text-gray-900">{{ $product->name }}</h1>

                <!-- Price -->
                <div class="text-2xl font-light text-gray-900">
                    <span>{{ format_price($countrySpecificPrice, $countrySpecificCurrency) }}</span>
                    @if($countrySpecificPrice != $product->price)
                        <span class="text-lg text-gray-400 line-through ml-2">{{ format_price($product->price, 'CAD') }}</span>
                    @endif
                </div>

                <!-- Country Availability Info -->
                @if(!$isAvailableInUserCountry)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <span class="text-yellow-800 text-sm">{{ __('This product is not available in your country') }} ({{ $userLocation['country_name'] }})</span>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-gray-600">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('Available in') }} {{ $userLocation['country_name'] }}
                        </span>
                        @if($countrySpecificStock > 0)
                            <span class="text-green-600 ml-2">• {{ $countrySpecificStock }} {{ __('in stock') }}</span>
                        @endif
                    </div>
                @endif

                <!-- Description -->
                @if($product->description)
                <p class="text-gray-600 font-light max-w-2xl mx-auto leading-relaxed">
                    {{ $product->description }}
                </p>
                @endif
            </div>
        </div>

        <!-- Error Message (Hidden by default) -->
        <div id="errorMessage" class="mt-16 max-w-md mx-auto hidden">
            <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-red-200 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-heading text-red-700 mb-2">{{ __('Order Failed') }}</h3>
                <p class="text-red-600 mb-4" id="errorText">{{ __('Please try again or contact support.') }}</p>
                <button onclick="showForm()" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg font-subheading hover:bg-red-700 transition-colors">
                    {{ __('Try Again') }}
                </button>
            </div>
        </div>

        <!-- Success Message (Hidden by default) -->
        <div id="successMessage" class="mt-16 max-w-md mx-auto hidden">
            <div class="bg-white rounded-2xl p-8 shadow-lg border-2 text-center" style="border-color: var(--color-secondary);">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background-color: rgba(var(--color-secondary-rgb), 0.1);">
                    <svg class="w-8 h-8" style="color: var(--color-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-heading mb-2" style="color: var(--color-primary);">{{ __('Order Confirmed!') }}</h3>
                <p class="text-gray-600 mb-4" id="successText">{{ __('Your order has been placed successfully.') }}</p>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-600 mb-2">{{ __('Order Number') }}:</p>
                    <p class="font-mono text-lg font-bold" style="color: var(--color-secondary);" id="orderNumber">-</p>
                </div>
                <div id="orderSummary" class="text-sm text-gray-600 space-y-1 mb-4"></div>
                <button onclick="location.reload()" 
                        class="px-6 py-2 rounded-lg font-subheading transition-colors" 
                        style="background-color: var(--color-secondary); color: var(--color-primary);"
                        onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='var(--color-secondary)';" 
                        onmouseout="this.style.backgroundColor='var(--color-secondary)'; this.style.color='var(--color-primary)';">
                    {{ __('Place Another Order') }}
                </button>
            </div>
        </div>

        <!-- Registration Promotion Banner -->
        <div class="mt-16 max-w-md mx-auto mb-8">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-6 border-2 border-dashed" style="border-color: var(--color-secondary);">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full mb-4" style="background-color: rgba(var(--color-secondary-rgb), 0.1);">
                        <svg class="w-6 h-6" style="color: var(--color-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-heading mb-2" style="color: var(--color-primary);">{{ __('Register & Save!') }}</h3>
                    <p class="text-sm text-gray-600 mb-4">{{ __('Create an account and get') }} <span class="font-bold" style="color: var(--color-secondary);">{{ $product->registration_discount ?? 5 }}% {{ __('discount') }}</span> {{ __('on this order') }}</p>
                    <div class="flex gap-2">
                        <button type="button" onclick="showRegistrationForm()" 
                                class="flex-1 py-2 px-4 rounded-lg font-subheading text-sm transition-colors border"
                                style="background-color: var(--color-secondary); color: var(--color-primary); border-color: var(--color-secondary);"
                                onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='var(--color-secondary)';" 
                                onmouseout="this.style.backgroundColor='var(--color-secondary)'; this.style.color='var(--color-primary)';">
                            {{ __('Register & Order') }}
                        </button>
                        <button type="button" onclick="showGuestForm()" 
                                class="flex-1 py-2 px-4 rounded-lg font-subheading text-sm transition-colors border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                            {{ __('Continue as Guest') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Form (Hidden by default) -->
        <div id="registrationForm" class="max-w-md mx-auto hidden">
            <form id="registerAndOrder" method="POST" action="{{ route('register-order.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                    <h3 class="text-lg font-heading mb-4" style="color: var(--color-primary);">{{ __('Create Account & Order') }}</h3>
                    
                    <!-- Account Details -->
                    <div class="space-y-4 mb-6">
                        <div>
                            <input type="text" name="username" required
                                   placeholder="{{ __('Username') }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors">
                        </div>
                        <div>
                            <input type="email" name="email" required
                                   placeholder="{{ __('Email address') }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors">
                        </div>
                        <div>
                            <input type="password" name="password" required
                                   placeholder="{{ __('Password') }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors">
                        </div>
                        <div>
                            <input type="text" name="full_name" required
                                   placeholder="{{ __('Full name') }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors">
                        </div>
                        <div>
                            <input type="tel" name="phone_number" required
                                   placeholder="{{ __('Phone number') }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors">
                        </div>
                    </div>

                    <!-- Country Detection -->
                    <div class="mb-4">
                        <label class="block text-sm font-subheading text-gray-700 mb-2">{{ __('Country') }}</label>
                        <select name="country" id="countrySelect" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors"
                                onchange="handleCountryChange(this.value)">
                            @foreach($countries as $code => $name)
                                <option value="{{ $code }}" {{ $userLocation['country_code'] === $code ? 'selected' : '' }}>
                                    {{ __($name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cities Dropdown (shown for supported countries) -->
                    <div id="citiesDropdown" class="mb-4 {{ !empty($cities) ? '' : 'hidden' }}">
                        <label class="block text-sm font-subheading text-gray-700 mb-2">{{ __('City') }}</label>
                        <select name="city" id="citySelect" {{ !empty($cities) ? 'required' : '' }}
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors">
                            <option value="">{{ __('Select City') }}</option>
                            @foreach($cities as $cityKey => $cityName)
                                <option value="{{ $cityKey }}" {{ $userLocation['city'] === $cityName ? 'selected' : '' }}>
                                    {{ $cityName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Address -->
                    <div class="mb-6">
                        <textarea name="address" required rows="2"
                                  placeholder="{{ __('Delivery address') }}"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors resize-none"></textarea>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-6">
                        <label class="text-sm font-subheading text-gray-700 mb-2 block">{{ __('Quantity') }}</label>
                        <div class="flex items-center justify-center space-x-4">
                            <button type="button" onclick="decreaseRegQty()"
                                    class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <input type="number" id="regQuantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity ?? 100 }}"
                                   class="w-20 text-center border-0 border-b border-gray-300 focus:border-primary focus:outline-none">
                            <button type="button" onclick="increaseRegQty()"
                                    class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Total with Discount -->
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-sm text-gray-600">
                                <span>{{ __('Subtotal') }}</span>
                                <span>{{ site_currency() === 'EUR' ? '€' : '$' }}<span id="regSubtotal">{{ number_format($product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price, 2) }}</span></span>
                            </div>
                            <div class="flex justify-between items-center text-sm" style="color: var(--color-secondary);">
                                <span>{{ __('Registration Discount') }} ({{ $product->registration_discount ?? 5 }}%)</span>
                                <span>-{{ site_currency() === 'EUR' ? '€' : '$' }}<span id="regDiscount">0.00</span></span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-bold border-t pt-2">
                                <span class="font-subheading text-gray-700">{{ __('Total') }}</span>
                                <span class="font-subheading text-gray-900">{{ site_currency() === 'EUR' ? '€' : '$' }}<span id="regTotalPrice">{{ number_format($product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price, 2) }}</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    @if($product->stock_quantity > 0)
                    <button type="submit"
                            class="w-full py-4 rounded-lg font-subheading transition-colors"
                            style="background-color: var(--color-secondary); color: var(--color-primary);"
                            onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='var(--color-secondary)';" 
                            onmouseout="this.style.backgroundColor='var(--color-secondary)'; this.style.color='var(--color-primary)';">
                        {{ __('Create Account & Place Order') }}
                    </button>
                    @else
                    <button type="button" disabled
                            class="w-full bg-gray-200 text-gray-400 py-4 rounded-lg font-subheading cursor-not-allowed">
                        {{ __('Out of Stock') }}
                    </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Simple Checkout Form -->
        <div id="orderForm" class="max-w-md mx-auto hidden">
            <form id="quickCheckout" method="POST" action="{{ route('guest-order.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- Quantity -->
                <div>
                    <label class="text-sm font-subheading text-gray-700 mb-2 block">{{ __('Quantity') }}</label>
                    <div class="flex items-center justify-center space-x-4">
                        <button type="button" onclick="decreaseQty()"
                                class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity ?? 100 }}"
                               class="w-20 text-center border-0 border-b border-gray-300 focus:border-primary focus:outline-none">
                        <button type="button" onclick="increaseQty()"
                                class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="space-y-4">
                    <div>
                        <input type="text" name="full_name" required
                               placeholder="{{ __('Your name') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors">
                    </div>

                    <div>
                        <input type="tel" name="phone_number" required
                               placeholder="{{ __('Phone number') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors">
                    </div>

                    <div>
                        <input type="email" name="email"
                               placeholder="{{ __('Email (optional)') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors">
                    </div>

                    <div>
                        <textarea name="address" required rows="2"
                                  placeholder="{{ __('Delivery address') }}"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:outline-none transition-colors resize-none"></textarea>
                    </div>
                </div>

                <!-- Total -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center text-lg">
                        <span class="font-subheading text-gray-700">{{ __('Total') }}</span>
                        <span class="font-subheading text-gray-900">{{ site_currency() === 'EUR' ? '€' : '$' }}<span id="totalPrice">{{ number_format($product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price, 2) }}</span></span>
                    </div>
                </div>

                <!-- Submit Button -->
                @if($product->stock_quantity > 0)
                <button type="submit"
                        class="w-full bg-primary text-white py-4 rounded-lg font-subheading hover:bg-primary-700 transition-colors">
                    {{ __('Place Order') }}
                </button>
                @else
                <button type="button" disabled
                        class="w-full bg-gray-200 text-gray-400 py-4 rounded-lg font-subheading cursor-not-allowed">
                    {{ __('Out of Stock') }}
                </button>
                @endif

                <!-- Trust Badge -->
                <div class="text-center space-y-2">
                    <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            {{ __('Secure') }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            {{ __('Fast Delivery') }}
                        </span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Related Products (Minimal) -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="mt-24">
            <h2 class="text-center text-xl font-subheading text-gray-900 mb-8">{{ __('You might also like') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts->take(4) as $relatedProduct)
                <a href="{{ route('products.show', [$relatedProduct->categories->first()?->slug ?? 'produits', $relatedProduct->slug]) }}"
                   class="group">
                    @php
                        $perfumeImages = [
                            'https://images.unsplash.com/photo-1541643600914-78b084683601?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1594736797933-d0301ba2fe65?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1528740561666-dc2479dc08ab?w=200&h=200&fit=crop',
                            'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=200&h=200&fit=crop'
                        ];
                        $relatedImageUrl = $perfumeImages[($relatedProduct->id - 1) % count($perfumeImages)];
                    @endphp
                    <img src="{{ $relatedImageUrl }}"
                         alt="{{ $relatedProduct->name }}"
                         class="w-full h-32 object-cover rounded-lg mb-2 group-hover:opacity-80 transition-opacity">
                    <h3 class="text-sm font-subheading text-gray-900 truncate">{{ $relatedProduct->name }}</h3>
                    <p class="text-sm text-gray-600">{{ site_currency() === 'EUR' ? '€' : '$' }}{{ number_format($relatedProduct->price, 2) }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
const productPrice = {{ $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price }};
const registrationDiscount = {{ $product->registration_discount ?? 5 }};

// Translation strings for JavaScript
const translations = {
    'Select City': '{{ __('Select City') }}',
    'Processing...': '{{ __('Processing...') }}',
    'Account created and order placed successfully!': '{{ __('Account created and order placed successfully!') }}', 
    'Product': '{{ __('Product') }}',
    'Quantity': '{{ __('Quantity') }}',
    'Subtotal': '{{ __('Subtotal') }}',
    'Discount': '{{ __('Discount') }}',
    'Total': '{{ __('Total') }}',
    'You have already registered recently. Please try logging in instead.': '{{ __('You have already registered recently. Please try logging in instead.') }}',
    'Please check your information and try again.': '{{ __('Please check your information and try again.') }}',
    'A network error occurred. Please check your connection and try again.': '{{ __('A network error occurred. Please check your connection and try again.') }}'
};

function updateTotal() {
    const quantity = document.getElementById('quantity').value;
    const total = (productPrice * quantity).toFixed(2);
    document.getElementById('totalPrice').textContent = total;
}

function updateRegistrationTotal() {
    const quantity = document.getElementById('regQuantity').value;
    const subtotal = productPrice * quantity;
    const discountAmount = subtotal * (registrationDiscount / 100);
    const total = subtotal - discountAmount;
    
    document.getElementById('regSubtotal').textContent = subtotal.toFixed(2);
    document.getElementById('regDiscount').textContent = discountAmount.toFixed(2);
    document.getElementById('regTotalPrice').textContent = total.toFixed(2);
}

// Form switching functions
function showRegistrationForm() {
    document.getElementById('registrationForm').classList.remove('hidden');
    document.getElementById('orderForm').classList.add('hidden');
    updateRegistrationTotal();
}

function showGuestForm() {
    document.getElementById('orderForm').classList.remove('hidden');
    document.getElementById('registrationForm').classList.add('hidden');
    updateTotal();
}

// Country change handler
function handleCountryChange(country) {
    const citiesDropdown = document.getElementById('citiesDropdown');
    const citySelect = document.getElementById('citySelect');
    
    if (!country || country === 'other') {
        citiesDropdown.classList.add('hidden');
        citySelect.removeAttribute('required');
        citySelect.value = '';
        return;
    }
    
    // Fetch cities for selected country
    fetch(`/api/cities/${country}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && Object.keys(data.cities).length > 0) {
                // Clear existing options
                citySelect.innerHTML = `<option value="">${translations['Select City']}</option>`;
                
                // Add new city options
                for (const [cityKey, cityName] of Object.entries(data.cities)) {
                    const option = document.createElement('option');
                    option.value = cityKey;
                    option.textContent = cityName;
                    citySelect.appendChild(option);
                }
                
                // Show the cities dropdown
                citiesDropdown.classList.remove('hidden');
                citySelect.setAttribute('required', 'required');
            } else {
                // Hide dropdown if no cities available
                citiesDropdown.classList.add('hidden');
                citySelect.removeAttribute('required');
                citySelect.value = '';
            }
        })
        .catch(error => {
            console.error('Error fetching cities:', error);
            citiesDropdown.classList.add('hidden');
            citySelect.removeAttribute('required');
        });
}

// Registration quantity functions
function increaseRegQty() {
    const input = document.getElementById('regQuantity');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
        updateRegistrationTotal();
    }
}

function decreaseRegQty() {
    const input = document.getElementById('regQuantity');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
        updateRegistrationTotal();
    }
}

function increaseQty() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
        updateTotal();
    }
}

function decreaseQty() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
        updateTotal();
    }
}

document.getElementById('quantity').addEventListener('input', updateTotal);
document.getElementById('regQuantity').addEventListener('input', updateRegistrationTotal);

// Registration form submission
document.getElementById('registerAndOrder').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = translations['Processing...'];
    submitBtn.disabled = true;

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => {
        return response.json().then(data => ({ status: response.status, data }));
    })
    .then(({ status, data }) => {
        if (status === 200 && data.success) {
            // Show success message with discount details
            showRegistrationSuccess(data);
            this.reset();
            updateRegistrationTotal();
        } else if (status === 429) {
            // Registration limit reached
            showError(translations['You have already registered recently. Please try logging in instead.']);
        } else if (data.errors) {
            // Validation errors
            const errorMessages = Object.values(data.errors).flat();
            showError(errorMessages.join(' '));
        } else {
            // Other errors
            showError(data.message || translations['Please check your information and try again.']);
        }
    })
    .catch(error => {
        console.error('Registration error:', error);
        showError(translations['A network error occurred. Please check your connection and try again.']);
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

// Helper functions for showing/hiding messages
function showForm() {
    document.getElementById('orderForm').classList.remove('hidden');
    document.getElementById('successMessage').classList.add('hidden');
    document.getElementById('errorMessage').classList.add('hidden');
}

function showSuccess(data) {
    // Hide forms and error message
    document.getElementById('orderForm').classList.add('hidden');
    document.getElementById('registrationForm').classList.add('hidden');
    document.getElementById('errorMessage').classList.add('hidden');
    
    // Update success message content
    document.getElementById('orderNumber').textContent = data.order_number;
    
    if (data.order_details) {
        const summary = document.getElementById('orderSummary');
        const currency = data.order_details.currency === 'EUR' ? '€' : '$';
        summary.innerHTML = `
            <p><strong>${translations['Product']}:</strong> ${data.order_details.product_name}</p>
            <p><strong>${translations['Quantity']}:</strong> ${data.order_details.quantity}</p>
            <p><strong>${translations['Total']}:</strong> ${currency}${parseFloat(data.order_details.total_price).toFixed(2)}</p>
        `;
    }
    
    // Show success message
    document.getElementById('successMessage').classList.remove('hidden');
    
    // Scroll to success message
    document.getElementById('successMessage').scrollIntoView({ behavior: 'smooth' });
}

function showRegistrationSuccess(data) {
    // Hide forms and error message
    document.getElementById('orderForm').classList.add('hidden');
    document.getElementById('registrationForm').classList.add('hidden');
    document.getElementById('errorMessage').classList.add('hidden');
    
    // Update success message content
    document.getElementById('orderNumber').textContent = data.order_number;
    
    if (data.order_details) {
        const summary = document.getElementById('orderSummary');
        const currency = data.order_details.currency === 'EUR' ? '€' : '$';
        summary.innerHTML = `
            <p><strong>${translations['Product']}:</strong> ${data.order_details.product_name}</p>
            <p><strong>${translations['Quantity']}:</strong> ${data.order_details.quantity}</p>
            <p><strong>${translations['Subtotal']}:</strong> ${currency}${parseFloat(data.order_details.subtotal).toFixed(2)}</p>
            <p><strong>${translations['Discount']} (${data.order_details.discount_percent}%):</strong> -${currency}${parseFloat(data.order_details.discount_amount).toFixed(2)}</p>
            <p><strong>${translations['Total']}:</strong> ${currency}${parseFloat(data.order_details.total_price).toFixed(2)}</p>
            <p class="text-xs text-gray-500 mt-2">✓ Account created successfully</p>
        `;
    }
    
    // Show success message
    document.getElementById('successMessage').classList.remove('hidden');
    
    // Scroll to success message
    document.getElementById('successMessage').scrollIntoView({ behavior: 'smooth' });
}

function showError(message) {
    // Hide forms and success message
    document.getElementById('orderForm').classList.add('hidden');
    document.getElementById('registrationForm').classList.add('hidden');
    document.getElementById('successMessage').classList.add('hidden');
    
    // Update error message
    document.getElementById('errorText').textContent = message || translations['A network error occurred. Please check your connection and try again.'];
    
    // Show error message
    document.getElementById('errorMessage').classList.remove('hidden');
    
    // Scroll to error message
    document.getElementById('errorMessage').scrollIntoView({ behavior: 'smooth' });
}

// Simple form submission
document.getElementById('quickCheckout').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = translations['Processing...'];
    submitBtn.disabled = true;

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => {
        return response.json().then(data => ({ status: response.status, data }));
    })
    .then(({ status, data }) => {
        if (status === 200 && data.success) {
            // Show beautiful success message
            showSuccess(data);
            this.reset();
            updateTotal();
        } else if (status === 429) {
            // Bot protection triggered
            showError(translations['You have already placed an order recently. Please wait before ordering again.'] || 'You have already placed an order recently. Please wait before ordering again.');
        } else if (data.errors) {
            // Validation errors
            const errorMessages = Object.values(data.errors).flat();
            showError(errorMessages.join(' '));
        } else {
            // Other errors
            showError(data.message || translations['Please check your information and try again.']);
        }
    })
    .catch(error => {
        console.error('Order error:', error);
        showError(translations['A network error occurred. Please check your connection and try again.']);
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});
</script>
@endsection