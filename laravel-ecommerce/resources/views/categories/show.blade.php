@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <div class="container mx-auto px-4 py-8">
        <!-- Category Header -->
        <div class="bg-white rounded-lg shadow p-8 mb-8">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-8">
                @if($category->image)
                    <div class="flex-shrink-0">
                        <img src="{{ $category->image }}"
                             alt="{{ $category->name }}"
                             class="w-32 h-32 object-cover rounded-lg">
                    </div>
                @else
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-4xl">📁</span>
                    </div>
                @endif

                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl font-heading text-gray-900 mb-4">{{ $category->name }}</h1>

                    @if($category->description)
                        <p class="text-gray-600 mb-4 font-light">{{ $category->description }}</p>
                    @endif

                    <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                        <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            {{ $products->total() }} @t('Products')
                        </span>

                        @if($category->parent)
                            <a href="{{ route('categories.show', $category->parent->slug) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                {{ $category->parent->name }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Subcategories -->
        @if($category->children && $category->children->count() > 0)
            <div class="mb-8">
                <h2 class="text-xl font-heading text-gray-900 mb-4">@t('Subcategories')</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($category->children as $child)
                        <a href="{{ route('categories.show', $child->slug) }}"
                           class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow duration-300">
                            <h3 class="font-subheading text-gray-900 mb-2">{{ $child->name }}</h3>
                            <p class="text-sm text-gray-600 font-light">{{ $child->products_count ?? 0 }} @t('products')</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Products Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-heading text-gray-900 mb-6">@t('Products in') {{ $category->name }}</h2>

            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="text-lg font-subheading text-gray-900 mb-2">@t('No products found')</h3>
                    <p class="text-gray-600 font-light">@t('This category doesn\'t have any products yet')</p>
                </div>
            @endif
        </div>

        <!-- Back to Categories -->
        <div class="text-center">
            <a href="{{ route('categories.index') }}"
               class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                @t('Back to Categories')
            </a>
        </div>
    </div>
</div>
@endsection