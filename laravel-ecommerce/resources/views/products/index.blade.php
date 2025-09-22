@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-heading text-gray-900 mb-4">@t('Products')</h1>
            <p class="text-gray-600 font-light">@t('Discover our complete product collection')</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:w-1/4">
                <form method="GET" action="{{ route('products.index') }}" id="filters-form">
                <div class="bg-white p-6" style="border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    <h3 class="font-subheading text-gray-900 mb-4">@t('Filters')</h3>
                    
                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-subheading text-gray-700 mb-2">@t('Search')</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="@t('Search products...')"
                               class="w-full px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                               style="border-radius: 8px;"
                               onchange="document.getElementById('filters-form').submit()">
                    </div>
                    
                    <!-- Categories -->
                    <div class="mb-6">
                        <label class="block text-sm font-subheading text-gray-700 mb-2">@t('Categories')</label>
                        <select name="category"
                                class="w-full px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                                style="border-radius: 8px;"
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
                                class="w-full px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                                style="border-radius: 8px;"
                                onchange="document.getElementById('filters-form').submit()">
                            <option value="">@t('All Brands')</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->slug }}" {{ request('brand') === $brand->slug ? 'selected' : '' }}>
                                    {{ $brand->name }}
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
                                   class="w-1/2 px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                                   style="border-radius: 8px;"
                                   onchange="document.getElementById('filters-form').submit()">
                            <input type="number"
                                   name="price_max"
                                   value="{{ request('price_max') }}"
                                   placeholder="@t('Max')"
                                   class="w-1/2 px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                                   style="border-radius: 8px;"
                                   onchange="document.getElementById('filters-form').submit()">
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-primary hover:bg-primary-700 text-white font-subheading py-3 px-4 transition-all duration-200 hover:scale-105 hover:shadow-lg"
                            style="border-radius: 12px;">
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
                                class="px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                                style="border-radius: 8px;"
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