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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-roboto-black text-white mb-4 tracking-wide">@t('Fragrance Categories')</h1>
                    <p class="text-xl text-gray-200 max-w-2xl mx-auto font-roboto-light">@t('Discover our carefully curated perfume collections organized by style, occasion, and fragrance family')</p>

                    <!-- Stats -->
                    <div class="flex justify-center items-center mt-8 space-x-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold" style="color: var(--color-secondary);">{{ $categories->count() }}</div>
                            <div class="text-sm text-gray-300 font-light">@t('Categories')</div>
                        </div>
                        <div class="w-px h-12 bg-gray-600"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold" style="color: var(--color-secondary);">{{ $categories->sum('products_count') ?? '100+' }}</div>
                            <div class="text-sm text-gray-300 font-light">@t('Products')</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        @if($categories->count() > 0)
            @php
                $gradientBackgrounds = [
                    'linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%)',
                    'linear-gradient(135deg, #f9f7f4 0%, #e8ddd3 100%)',
                    'linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%)',
                    'linear-gradient(135deg, #fefefe 0%, #f0f0f0 100%)',
                    'linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%)',
                    'linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%)',
                ];

                $categoryImages = [
                    'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=400&h=300&fit=crop&crop=center',
                    'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400&h=300&fit=crop&crop=center',
                    'https://images.unsplash.com/photo-1541643600914-78b084683601?w=400&h=300&fit=crop&crop=center',
                    'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=300&fit=crop&crop=center',
                    'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=400&h=300&fit=crop&crop=center',
                    'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=400&h=300&fit=crop&crop=center',
                ];
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                @foreach($categories as $category)
                    @php
                        $gradientBg = $gradientBackgrounds[($loop->index) % count($gradientBackgrounds)];
                        $categoryImageUrl = $categoryImages[($loop->index) % count($categoryImages)];
                    @endphp
                    <div class="group relative flex flex-col bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 transform hover:-translate-y-2" onmouseover="this.style.borderColor='rgba(var(--color-secondary-rgb), 0.3)';" onmouseout="this.style.borderColor='#f3f4f6';">
                        <a href="{{ route('categories.show', $category->slug) }}" class="block flex flex-col h-full">
                            <!-- Image Container -->
                            <div class="relative h-56 overflow-hidden rounded-t-3xl" style="background: {{ $gradientBg }};">
                                @if($category->image)
                                    <img src="{{ $category->image }}"
                                         alt="{{ $category->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                @else
                                    <img src="{{ $categoryImageUrl }}"
                                         alt="{{ $category->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                @endif

                                <!-- Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity duration-300"></div>

                                <!-- Product Count Badge -->
                                <div class="absolute top-4 right-4 backdrop-blur-sm rounded-full px-3 py-1.5 shadow-lg" style="background-color: rgba(var(--color-secondary-rgb), 0.95);">
                                    <span class="text-xs font-roboto-medium" style="color: var(--color-primary);">{{ $category->products_count ?? 0 }}</span>
                                </div>

                                <!-- Category Name Overlay -->
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="text-lg font-roboto-bold text-white mb-1 transition-colors duration-300" onmouseover="this.style.color='var(--color-secondary)';" onmouseout="this.style.color='white';">{{ $category->name }}</h3>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow p-5 flex flex-col">
                                @if($category->description)
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2 font-roboto-light flex-grow">{{ Str::limit($category->description, 100) }}</p>
                                @endif

                                <div class="flex items-center justify-between mt-auto">
                                    <span class="text-sm text-gray-500 font-roboto-light">
                                        {{ $category->products_count ?? 0 }} @t('products')
                                    </span>
                                    <span class="text-sm font-roboto-medium group-hover:translate-x-1 transition-transform inline-flex items-center" style="color: var(--color-secondary);">
                                        @t('View Products')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                </div>

                                @if($category->children && $category->children->count() > 0)
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <p class="text-xs text-gray-500 mb-2 font-roboto-light">@t('Subcategories'):</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($category->children->take(3) as $child)
                                                <span class="inline-block px-3 py-1 bg-gray-50 text-xs text-gray-700 rounded-full border transition-colors font-roboto-light" onmouseover="this.style.backgroundColor='rgba(var(--color-secondary-rgb), 0.1)';" onmouseout="this.style.backgroundColor='#f9fafb';">
                                                    {{ $child->name }}
                                                </span>
                                            @endforeach
                                            @if($category->children->count() > 3)
                                                <span class="inline-block px-3 py-1 text-xs font-roboto-medium rounded-full" style="background-color: rgba(var(--color-secondary-rgb), 0.1); color: var(--color-secondary);">
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