@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <div class="container mx-auto px-4 py-8">
        <!-- Category Header -->
        <div class="relative mb-12 rounded-3xl overflow-hidden">
            <div class="p-12 relative" style="background: linear-gradient(to right, var(--color-primary), #374151, var(--color-primary));">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><defs><pattern id=\"grid\" width=\"10\" height=\"10\" patternUnits=\"userSpaceOnUse\"><path d=\"M 10 0 L 0 0 0 10\" fill=\"none\" stroke=\"%23ffffff\" stroke-width=\"0.5\"/></pattern></defs><rect width=\"100\" height=\"100\" fill=\"url(%23grid)\"/></svg>');"></div>
                </div>

                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                    @if($category->image)
                        <div class="flex-shrink-0">
                            <img src="{{ $category->image }}"
                                 alt="{{ $category->name }}"
                                 class="w-32 h-32 object-cover rounded-2xl shadow-2xl"
                                 style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 4px rgba(var(--color-secondary-rgb), 0.3);">
                        </div>
                    @else
                        <div class="w-32 h-32 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-2xl"
                             style="background: linear-gradient(135deg, var(--color-secondary) 0%, rgba(var(--color-secondary-rgb), 0.7) 100%); 
                                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 4px rgba(var(--color-secondary-rgb), 0.3);"
                            <svg class="w-16 h-16" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <span class="inline-flex items-center px-6 py-3 rounded-xl shadow-lg font-roboto-medium"
                                  style="background-color: rgba(var(--color-secondary-rgb), 0.9); color: var(--color-primary); border: 1px solid var(--color-secondary);"
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                {{ $products->total() }} @t('Products')
                            </span>

                            @if($category->parent)
                                <a href="{{ route('categories.show', $category->parent->slug) }}"
                                   class="inline-flex items-center px-6 py-3 backdrop-blur-sm rounded-xl transition-all duration-300 font-roboto-medium"
                                   style="background-color: rgba(0, 0, 0, 0.5); color: var(--color-secondary); border: 2px solid var(--color-secondary);"
                                   onmouseover="this.style.backgroundColor='var(--color-secondary)'; this.style.color='var(--color-primary)';" 
                                   onmouseout="this.style.backgroundColor='rgba(0, 0, 0, 0.5)'; this.style.color='var(--color-secondary)';"
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
                           class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border border-gray-100 transform hover:-translate-y-1"
                           onmouseover="this.style.borderColor='rgba(var(--color-secondary-rgb), 0.3)';" 
                           onmouseout="this.style.borderColor='';"
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform"
                                     style="background: linear-gradient(135deg, var(--color-secondary) 0%, rgba(var(--color-secondary-rgb), 0.7) 100%);">
                                    <svg class="w-6 h-6" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-roboto-medium text-gray-900 mb-1 transition-colors group-hover:text-gray-600" 
                                        onmouseover="this.style.color='var(--color-secondary)';" 
                                        onmouseout="this.style.color='';">{{ $child->name }}</h3>
                                    <p class="text-sm text-gray-600 font-roboto-light">{{ $child->products_count ?? 0 }} @t('products')</p>
                                </div>
                            </div>
                            <div class="flex items-center group-hover:translate-x-2 transition-transform" style="color: var(--color-secondary);">
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
               class="inline-flex items-center px-8 py-4 rounded-2xl transition-all duration-300 font-roboto-medium shadow-lg"
               style="background-color: var(--color-primary); color: var(--color-secondary); border: 2px solid var(--color-secondary);"
               onmouseover="this.style.backgroundColor='var(--color-secondary)'; this.style.color='var(--color-primary)';" 
               onmouseout="this.style.backgroundColor='var(--color-primary)'; this.style.color='var(--color-secondary)';"
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                @t('Back to Categories')
            </a>
        </div>
    </div>
</div>
@endsection