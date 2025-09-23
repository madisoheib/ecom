@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <div class="container mx-auto px-4 py-8">
        <!-- Category Header -->
        <div class="relative mb-12 rounded-3xl overflow-hidden">
            <div class="bg-gradient-to-r from-primary via-gray-900 to-primary p-12 relative">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><defs><pattern id=\"grid\" width=\"10\" height=\"10\" patternUnits=\"userSpaceOnUse\"><path d=\"M 10 0 L 0 0 0 10\" fill=\"none\" stroke=\"%23ffffff\" stroke-width=\"0.5\"/></pattern></defs><rect width=\"100\" height=\"100\" fill=\"url(%23grid)\"/></svg>');"></div>
                </div>

                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                    @if($category->image)
                        <div class="flex-shrink-0">
                            <img src="{{ $category->image }}"
                                 alt="{{ $category->name }}"
                                 class="w-32 h-32 object-cover rounded-2xl shadow-2xl ring-4 ring-secondary/30">
                        </div>
                    @else
                        <div class="w-32 h-32 bg-gradient-to-br from-secondary to-secondary/70 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-2xl ring-4 ring-secondary/30">
                            <svg class="w-16 h-16 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    @endif

                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-4xl md:text-5xl font-roboto-black text-white mb-4 tracking-wide">{{ $category->name }}</h1>

                        @if($category->description)
                            <p class="text-xl text-gray-200 mb-6 font-roboto-light max-w-2xl">{{ $category->description }}</p>
                        @endif

                        <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                            <span class="inline-flex items-center px-6 py-3 bg-secondary/90 text-black rounded-xl border border-secondary shadow-lg font-roboto-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                {{ $products->total() }} @t('Products')
                            </span>

                            @if($category->parent)
                                <a href="{{ route('categories.show', $category->parent->slug) }}"
                                   class="inline-flex items-center px-6 py-3 bg-black/50 backdrop-blur-sm text-secondary rounded-xl hover:bg-secondary hover:text-black transition-all duration-300 border-2 border-secondary font-roboto-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    {{ $category->parent->name }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subcategories -->
        @if($category->children && $category->children->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-roboto-bold text-gray-900 mb-6">@t('Subcategories')</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($category->children as $child)
                        <a href="{{ route('categories.show', $child->slug) }}"
                           class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border border-gray-100 hover:border-secondary/30 transform hover:-translate-y-1">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-secondary to-secondary/70 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-roboto-medium text-gray-900 mb-1 group-hover:text-secondary transition-colors">{{ $child->name }}</h3>
                                    <p class="text-sm text-gray-600 font-roboto-light">{{ $child->products_count ?? 0 }} @t('products')</p>
                                </div>
                            </div>
                            <div class="flex items-center text-secondary group-hover:translate-x-2 transition-transform">
                                <span class="text-sm font-roboto-medium">@t('View Category')</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Products Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-roboto-bold text-gray-900 mb-6">@t('Products in') {{ $category->name }}</h2>

            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
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
                    <h3 class="text-lg font-roboto-medium text-gray-900 mb-2">@t('No products found')</h3>
                    <p class="text-gray-600 font-roboto-light">@t('This category doesn\'t have any products yet')</p>
                </div>
            @endif
        </div>

        <!-- Back to Categories -->
        <div class="text-center">
            <a href="{{ route('categories.index') }}"
               class="inline-flex items-center px-8 py-4 bg-primary text-secondary rounded-2xl hover:bg-secondary hover:text-primary transition-all duration-300 border-2 border-secondary font-roboto-medium shadow-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                @t('Back to Categories')
            </a>
        </div>
    </div>
</div>
@endsection