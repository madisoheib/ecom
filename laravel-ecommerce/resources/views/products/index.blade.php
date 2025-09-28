@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <div class="container mx-auto px-4 py-8">
        <!-- Modern Header with Background -->
        <div class="relative mb-12 rounded-2xl overflow-hidden">
            <div class="p-12 text-center relative" style="background: linear-gradient(to right, var(--color-primary), #374151, var(--color-primary));">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><defs><pattern id=\"grid\" width=\"10\" height=\"10\" patternUnits=\"userSpaceOnUse\"><path d=\"M 10 0 L 0 0 0 10\" fill=\"none\" stroke=\"%23ffffff\" stroke-width=\"0.5\"/></pattern></defs><rect width=\"100\" height=\"100\" fill=\"url(%23grid)\"/></svg>');"></div>
                </div>

                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-6 shadow-lg" style="background-color: var(--color-secondary);">
                        <svg class="w-10 h-10" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M8 11v6a4 4 0 008 0v-6M8 11h8"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-roboto-black text-white mb-4 tracking-wide">@t('Our Products')</h1>
                    <p class="text-xl text-gray-200 max-w-2xl mx-auto font-roboto-light">@t('Discover our complete collection of premium products')</p>

                    <!-- Stats -->
                    <div class="flex justify-center items-center mt-8 space-x-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold" style="color: var(--color-secondary);">{{ $products->total() }}</div>
                            <div class="text-sm text-gray-300 font-light">@t('Products')</div>
                        </div>
                        <div class="w-px h-12 bg-gray-600"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold" style="color: var(--color-secondary);">{{ $brands->count() }}</div>
                            <div class="text-sm text-gray-300 font-light">@t('Brands')</div>
                        </div>
                        <div class="w-px h-12 bg-gray-600"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold" style="color: var(--color-secondary);">{{ $categories->count() }}</div>
                            <div class="text-sm text-gray-300 font-light">@t('Categories')</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:w-1/4">
                <form method="GET" action="{{ route('products.index') }}" id="filters-form">
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                    <h3 class="font-bold text-xl mb-6 pb-3 border-b" style="color: var(--color-primary); border-color: rgba(var(--color-secondary-rgb), 0.2);">@t('Filters')</h3>
                    
                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-subheading text-gray-700 mb-2">@t('Search')</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="@t('Search products...')"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:border-gray-400 transition-all duration-300"
                               onchange="document.getElementById('filters-form').submit()">
                    </div>
                    
                    <!-- Categories -->
                    <div class="mb-6">
                        <label class="block text-sm font-subheading text-gray-700 mb-2">@t('Categories')</label>
                        <select name="category"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:border-gray-400 transition-all duration-300"
                                onchange="document.getElementById('filters-form').submit()">
                            <option value="">@t('All Categories')</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Brands -->
                    <div class="mb-6">
                        <label class="block text-sm font-subheading text-gray-700 mb-2">@t('Brands')</label>
                        <select name="brand"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:border-gray-400 transition-all duration-300"
                                onchange="document.getElementById('filters-form').submit()">
                            <option value="">@t('All Brands')</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->slug }}" {{ request('brand') === $brand->slug ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Countries -->
                    <div class="mb-6">
                        <label class="block text-sm font-subheading text-gray-700 mb-2">@t('Available in')</label>
                        <select name="country"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:border-gray-400 transition-all duration-300"
                                onchange="document.getElementById('filters-form').submit()">
                            @foreach($availableCountries as $code => $name)
                                <option value="{{ $code }}" {{ $selectedCountry === $code ? 'selected' : '' }}>
                                    {{ $name }}
                                    @if($code === $userLocation['country_code'] && $code !== 'all')
                                        <span class="text-green-600">({{ __('Your Location') }})</span>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="mb-6">
                        <label class="block text-sm font-subheading text-gray-700 mb-2">@t('Price Range')</label>
                        <div class="flex gap-2">
                            <input type="number"
                                   name="price_min"
                                   value="{{ request('price_min') }}"
                                   placeholder="@t('Min')"
                                   class="w-1/2 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:border-gray-400 transition-all duration-300"
                                   onchange="document.getElementById('filters-form').submit()">
                            <input type="number"
                                   name="price_max"
                                   value="{{ request('price_max') }}"
                                   placeholder="@t('Max')"
                                   class="w-1/2 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:border-gray-400 transition-all duration-300"
                                   onchange="document.getElementById('filters-form').submit()">
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full py-3 px-4 rounded-lg font-medium transition-all duration-300 hover:shadow-lg"
                            style="background-color: var(--color-secondary); color: var(--color-primary);"
                            onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='var(--color-secondary)';" 
                            onmouseout="this.style.backgroundColor='var(--color-secondary)'; this.style.color='var(--color-primary)';">
                        @t('Apply Filters')
                    </button>
                </div>
                </form>
            </div>
            
            <!-- Products Grid -->
            <div class="lg:w-3/4">
                <!-- Sort Options -->
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600 font-light">{{ $products->total() }} @t('products found')</p>

                    <form method="GET" action="{{ route('products.index') }}" class="inline">
                        <!-- Preserve existing filters -->
                        @foreach(request()->except(['sort', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <select name="sort"
                                class="px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:border-gray-400 transition-all duration-300"
                                onchange="this.form.submit()">
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>@t('Sort by Name')</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>@t('Price: Low to High')</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>@t('Price: High to Low')</option>
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>@t('Newest First')</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>@t('Most Popular')</option>
                        </select>
                    </form>
                </div>
                
                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="flex justify-center">
                        {{ $products->appends(request()->query())->links('pagination.custom') }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-subheading text-gray-900 mb-2">@t('No products found')</h3>
                        <p class="text-gray-600 font-light">@t('Try adjusting your search criteria')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection