@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">@t('Brands')</h1>
            <p class="text-gray-600">@t('Discover all our trusted brands')</p>
        </div>

        <!-- Brands Grid -->
        @if($brands->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                @foreach($brands as $brand)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                        <a href="{{ route('brands.show', $brand->slug) }}" class="block p-6 text-center">
                            @if($brand->logo)
                                <div class="mb-4">
                                    <img src="{{ $brand->logo }}"
                                         alt="{{ $brand->name }}"
                                         class="w-16 h-16 mx-auto object-contain">
                                </div>
                            @else
                                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-400">{{ substr($brand->name, 0, 1) }}</span>
                                </div>
                            @endif

                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $brand->name }}</h3>

                            @if($brand->description)
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($brand->description, 80) }}</p>
                            @endif

                            <span class="text-sm text-primary hover:text-primary-700">
                                @t('View Products') →
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $brands->links('pagination.custom') }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">@t('No brands found')</h3>
                <p class="text-gray-600">@t('Brands will appear here once they are added')</p>
            </div>
        @endif
    </div>
</div>
@endsection