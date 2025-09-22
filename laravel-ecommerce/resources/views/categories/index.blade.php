@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <div class="container mx-auto px-4 py-8">
        <!-- Modern Header with Background -->
        <div class="relative mb-12 rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-primary via-gray-900 to-primary p-12 text-center relative">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><defs><pattern id=\"grid\" width=\"10\" height=\"10\" patternUnits=\"userSpaceOnUse\"><path d=\"M 10 0 L 0 0 0 10\" fill=\"none\" stroke=\"%23ffffff\" stroke-width=\"0.5\"/></pattern></defs><rect width=\"100\" height=\"100\" fill=\"url(%23grid)\"/></svg>');"></div>
                </div>

                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-secondary rounded-full mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-heading text-white mb-4">@t('Categories')</h1>
                    <p class="text-xl text-gray-200 max-w-2xl mx-auto font-light">@t('Discover our carefully curated perfume collections organized by style, occasion, and fragrance family')</p>

                    <!-- Stats -->
                    <div class="flex justify-center items-center mt-8 space-x-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-secondary">{{ $categories->count() }}</div>
                            <div class="text-sm text-gray-300 font-light">@t('Categories')</div>
                        </div>
                        <div class="w-px h-12 bg-gray-600"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-secondary">{{ $categories->sum('products_count') ?? '100+' }}</div>
                            <div class="text-sm text-gray-300 font-light">@t('Products')</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                @foreach($categories as $category)
                    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-secondary">
                        <a href="{{ route('categories.show', $category->slug) }}" class="block">
                            @if($category->image)
                                <div class="h-48 bg-cover bg-center relative" style="background-image: url('{{ $category->image }}')">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    <div class="absolute bottom-4 left-4 text-white">
                                        <h3 class="text-lg font-semibold">{{ $category->name }}</h3>
                                    </div>
                                    <div class="absolute top-4 right-4 bg-secondary/90 backdrop-blur-sm rounded-full px-3 py-1">
                                        <span class="text-xs font-semibold text-primary">{{ $category->products_count ?? 0 }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="h-48 bg-gradient-to-br from-primary via-gray-800 to-secondary relative overflow-hidden">
                                    <!-- Decorative elements -->
                                    <div class="absolute inset-0 opacity-20">
                                        <div class="absolute top-4 right-4 w-16 h-16 border border-white/30 rounded-full"></div>
                                        <div class="absolute bottom-8 left-8 w-8 h-8 bg-secondary/40 rounded-full"></div>
                                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-32 h-32 border border-white/20 rounded-full"></div>
                                    </div>

                                    <div class="relative h-full flex flex-col justify-center items-center text-white p-6">
                                        <div class="w-16 h-16 bg-secondary/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                            <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-center">{{ $category->name }}</h3>
                                        <div class="absolute top-4 right-4 bg-secondary/90 backdrop-blur-sm rounded-full px-3 py-1">
                                            <span class="text-xs font-semibold text-primary">{{ $category->products_count ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="p-6">
                                @if($category->description)
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($category->description, 100) }}</p>
                                @endif

                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">
                                        {{ $category->products_count ?? 0 }} @t('products')
                                    </span>
                                    <span class="text-sm text-secondary hover:text-secondary font-semibold group-hover:translate-x-1 transition-transform inline-flex items-center">
                                        @t('View Products')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                </div>

                                @if($category->children && $category->children->count() > 0)
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <p class="text-xs text-gray-500 mb-2 font-light">@t('Subcategories'):</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($category->children->take(3) as $child)
                                                <span class="inline-block px-3 py-1 bg-gray-50 hover:bg-secondary/10 text-xs text-gray-700 rounded-full border transition-colors">
                                                    {{ $child->name }}
                                                </span>
                                            @endforeach
                                            @if($category->children->count() > 3)
                                                <span class="inline-block px-3 py-1 bg-secondary/10 text-xs text-secondary font-semibold rounded-full">
                                                    +{{ $category->children->count() - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="text-lg font-subheading text-gray-900 mb-2">@t('No categories found')</h3>
                <p class="text-gray-600 font-light">@t('Categories will appear here once they are added')</p>
            </div>
        @endif
    </div>
</div>
@endsection