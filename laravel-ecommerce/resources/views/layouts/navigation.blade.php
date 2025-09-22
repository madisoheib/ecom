<nav class="bg-white border-b border-gray-100 sticky top-0 z-50" style="box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);" x-data="{ mobileMenuOpen: false, userMenuOpen: false, categoriesOpen: false }">
    <!-- Top Bar -->
    <div class="text-white py-2 sm:py-3 bg-black">
        <div class="container mx-auto px-4 flex justify-between items-center text-xs sm:text-sm">
            <div class="flex items-center space-x-4 sm:space-x-6 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                <div class="hidden sm:flex items-center space-x-2">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>
                    <span class="hidden md:inline">+33 1 23 45 67 89</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                    </svg>
                    <span class="hidden sm:inline">contact@example.com</span>
                </div>
            </div>

            <!-- Language Switcher -->
            <div class="flex items-center space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 hover:text-gray-200 transition-colors duration-200 px-3 py-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-subheading">{{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-3 w-36 bg-white shadow-lg py-2 z-50" style="border-radius: 8px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);">
                        <a href="{{ url('/') }}?lang=fr" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 flex items-center space-x-2">
                            <span class="text-lg">🇫🇷</span>
                            <span>Français</span>
                        </a>
                        <a href="{{ url('/') }}?lang=en" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 flex items-center space-x-2">
                            <span class="text-lg">🇺🇸</span>
                            <span>English</span>
                        </a>
                        <a href="{{ url('/') }}?lang=ar" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 flex items-center space-x-2">
                            <span class="text-lg">🇸🇦</span>
                            <span>العربية</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Navigation -->
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16 sm:h-18">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 sm:space-x-3 group">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center transition-all duration-300 group-hover:scale-105 bg-black rounded-xl border-2 border-secondary">
                        <span class="text-secondary font-bold text-lg sm:text-xl">E</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg sm:text-2xl font-heading text-gray-900 leading-none">@t('E-Commerce')</span>
                        <span class="text-xs text-gray-500 font-body tracking-wide hidden sm:block">Premium Store</span>
                    </div>
                </a>
            </div>

            <!-- Search Bar -->
            <div class="hidden lg:flex flex-1 max-w-md xl:max-w-lg mx-6 xl:mx-8 my-3">
                <div class="relative w-full">
                    <input type="text" id="search-input"
                           placeholder="@t('Search products...')"
                           class="w-full pl-10 xl:pl-12 pr-10 xl:pr-12 py-2.5 xl:py-3 text-sm xl:text-base bg-gray-50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-secondary focus:border-secondary transition-all duration-300"
                           style="border-radius: 25px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); margin-top: 4px; margin-bottom: 4px;"
                           @keypress.enter="search()">
                    <div class="absolute inset-y-0 left-0 pl-3 xl:pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 xl:h-5 xl:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button onclick="search()" class="absolute inset-y-0 right-0 pr-3 xl:pr-4 flex items-center hover:scale-110 transition-transform duration-200">
                        <div class="w-7 h-7 xl:w-8 xl:h-8 flex items-center justify-center bg-black text-secondary rounded-full hover:bg-secondary hover:text-black transition-all duration-200">
                            <svg class="h-3.5 w-3.5 xl:h-4 xl:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Right Menu -->
            <div class="flex items-center space-x-3 sm:space-x-4 lg:space-x-6 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                <!-- Cart -->
                <button @click="$dispatch('cart-toggle')" class="relative group p-2 sm:p-3 hover:bg-gray-50 transition-all duration-200" style="border-radius: 12px;">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600 group-hover:text-black transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-secondary text-black text-xs font-bold rounded-full h-5 w-5 sm:h-6 sm:w-6 flex items-center justify-center transform group-hover:scale-110 transition-transform duration-200" x-text="window.cartData.count">0</span>
                </button>
                
                <!-- User Menu -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="hidden lg:block text-sm">{{ Auth::user()->name }}</span>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">@t('Dashboard')</a>
                            <a href="{{ route('user.orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">@t('My Orders')</a>
                            <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">@t('Profile')</a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">@t('Logout')</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-black font-subheading transition-colors duration-200 px-2 sm:px-4 py-2 hover:bg-gray-50 rounded-lg text-sm sm:text-base">@t('Login')</a>
                    <a href="{{ route('register') }}" class="bg-black text-secondary font-subheading px-3 sm:px-6 py-2 sm:py-2.5 text-sm sm:text-base transition-all duration-200 hover:bg-secondary hover:text-black rounded-xl border-2 border-secondary">@t('Register')</a>
                @endauth
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 sm:p-3 text-gray-600 hover:text-black hover:bg-gray-50 transition-all duration-200 rounded-xl">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Categories Menu -->
        <div class="border-t border-gray-100 py-3 sm:py-4 hidden lg:block" style="background: rgba(248, 249, 250, 0.5);">
            <div class="flex items-center justify-center space-x-4 xl:space-x-8 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-black font-subheading px-3 xl:px-4 py-2 hover:bg-white transition-all duration-200 group rounded-lg text-sm xl:text-base">
                        <div class="w-5 h-5 xl:w-6 xl:h-6 flex items-center justify-center">
                            <svg class="w-4 h-4 xl:w-5 xl:h-5 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </div>
                        <span>@t('Categories')</span>
                        <svg class="w-3.5 h-3.5 xl:w-4 xl:h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="absolute top-full left-0 mt-3 w-72 bg-white shadow-xl py-3 z-50" style="border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);">
                        @php
                            $categories = App\Models\Category::whereNull('parent_id')
                                ->where('is_active', true)
                                ->orderBy('sort_order')
                                ->limit(8)
                                ->get();
                        @endphp
                        @foreach($categories as $category)
                            <a href="{{ route('categories.show', $category->slug) }}"
                               class="block px-5 py-3 text-sm text-gray-700 hover:bg-secondary hover:text-black transition-all duration-150 mx-2 rounded-lg">
                                {{ $category->name }}
                            </a>
                        @endforeach
                        <hr class="my-3 mx-2">
                        <a href="{{ route('categories.index') }}" class="block px-5 py-3 text-sm text-black hover:bg-secondary font-medium transition-all duration-150 mx-2 rounded-lg">
                            @t('View All Categories')
                        </a>
                    </div>
                </div>

                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-black font-subheading px-3 xl:px-4 py-2 hover:bg-white transition-all duration-200 rounded-lg text-sm xl:text-base">@t('Products')</a>
                <a href="{{ route('brands.index') }}" class="text-gray-700 hover:text-black font-subheading px-3 xl:px-4 py-2 hover:bg-white transition-all duration-200 rounded-lg text-sm xl:text-base">@t('Brands')</a>
                <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="text-gray-700 hover:text-black font-subheading px-3 xl:px-4 py-2 hover:bg-white transition-all duration-200 rounded-lg text-sm xl:text-base">@t('New Arrivals')</a>
                <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="text-gray-700 hover:text-black font-subheading px-3 xl:px-4 py-2 hover:bg-white transition-all duration-200 rounded-lg text-sm xl:text-base">@t('Best Sellers')</a>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="lg:hidden border-t border-gray-100 bg-white">
        <div class="px-4 py-4 sm:py-6 space-y-4">
            <!-- Mobile Search -->
            <div class="relative">
                <input type="text" id="mobile-search-input" placeholder="@t('Search products...')"
                       class="w-full pl-10 pr-4 py-3 text-sm bg-gray-50 border-0 focus:bg-white focus:ring-2 focus:ring-secondary transition-all duration-300"
                       style="border-radius: 12px;"
                       @keypress.enter="search()">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Mobile Navigation Links -->
            <div class="space-y-1">
                <a href="{{ route('categories.index') }}" class="block text-gray-700 hover:text-black hover:bg-gray-50 px-4 py-3 font-medium transition-all duration-200 text-sm" style="border-radius: 10px;">@t('Categories')</a>
                <a href="{{ route('products.index') }}" class="block text-gray-700 hover:text-black hover:bg-gray-50 px-4 py-3 font-medium transition-all duration-200 text-sm" style="border-radius: 10px;">@t('Products')</a>
                <a href="{{ route('brands.index') }}" class="block text-gray-700 hover:text-black hover:bg-gray-50 px-4 py-3 font-medium transition-all duration-200 text-sm" style="border-radius: 10px;">@t('Brands')</a>
                <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="block text-gray-700 hover:text-black hover:bg-gray-50 px-4 py-3 font-medium transition-all duration-200 text-sm" style="border-radius: 10px;">@t('New Arrivals')</a>
                <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="block text-gray-700 hover:text-black hover:bg-gray-50 px-4 py-3 font-medium transition-all duration-200 text-sm" style="border-radius: 10px;">@t('Best Sellers')</a>
            </div>
        </div>
    </div>
</nav>