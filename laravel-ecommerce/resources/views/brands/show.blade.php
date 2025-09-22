@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <div class="container mx-auto px-4 py-8">
        <!-- Brand Header -->
        <div class="bg-white rounded-lg shadow p-8 mb-8">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-8">
                @if($brand->logo)
                    <div class="flex-shrink-0">
                        <img src="{{ $brand->logo }}"
                             alt="{{ $brand->name }}"
                             class="w-32 h-32 object-contain rounded-lg border border-gray-200">
                    </div>
                @else
                    <div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-4xl font-bold text-gray-400">{{ substr($brand->name, 0, 1) }}</span>
                    </div>
                @endif

                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $brand->name }}</h1>

                    @if($brand->description)
                        <p class="text-gray-600 mb-4">{{ $brand->description }}</p>
                    @endif

                    <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                        @if($brand->website)
                            <a href="{{ $brand->website }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                @t('Visit Website')
                            </a>
                        @endif

                        <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            {{ $products->total() }} @t('Products')
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">@t('Products from') {{ $brand->name }}</h2>

            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $products->links('pagination.custom') }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">@t('No products found')</h3>
                    <p class="text-gray-600">@t('This brand doesn\'t have any products yet')</p>
                </div>
            @endif
        </div>

        <!-- Back to Brands -->
        <div class="text-center">
            <a href="{{ route('brands.index') }}"
               class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                @t('Back to Brands')
            </a>
        </div>
    </div>
</div>
@endsection