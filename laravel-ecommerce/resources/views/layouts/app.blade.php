<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! isset($meta) ? App\Helpers\SeoHelper::renderMetaTags(isset($product) ? $product : null) : '' !!}
    
    @if(isset($breadcrumbSchema))
        {!! App\Helpers\SeoHelper::renderSchema($breadcrumbSchema) !!}
    @endif
    
    @if(isset($productSchema))
        {!! App\Helpers\SeoHelper::renderSchema($productSchema) !!}
    @endif

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Lato', 'system-ui', 'sans-serif'],
                        'arabic': ['Noto Sans Arabic', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        'custom-bg': '#f8f9fa',
                        'custom-primary': '#000000',
                        'custom-secondary': '#FFD700',
                        primary: {
                            DEFAULT: '#000000',
                            50: '#f8f8f8',
                            100: '#e7e7e7',
                            200: '#d1d1d1',
                            300: '#b0b0b0',
                            400: '#888888',
                            500: '#6d6d6d',
                            600: '#5d5d5d',
                            700: '#4f4f4f',
                            800: '#454545',
                            900: '#3d3d3d',
                            950: '#000000',
                        },
                        secondary: {
                            DEFAULT: '#FFD700',
                            50: '#fffef7',
                            100: '#fffaeb',
                            200: '#fff3c7',
                            300: '#ffe998',
                            400: '#ffd74f',
                            500: '#ffc726',
                            600: '#ffb31a',
                            700: '#e6940f',
                            800: '#cc7a0d',
                            900: '#a6620a',
                            950: '#613902',
                        },
                        gold: '#FFD700'
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- shadcn/ui styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@radix-ui/themes@2.0.0/styles.css">

    <!-- Inter font for shadcn -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Heroicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/heroicons@2.0.18/24/outline/style.css">
    
    <style>
        [x-cloak] { display: none !important; }
        .rtl { direction: rtl; }
        .arabic { font-family: 'Noto Sans Arabic', system-ui, sans-serif; }

        /* Fix for RTL spacing utilities */
        .rtl .space-x-reverse > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }

        /* Lato font for English and French */
        body { font-family: 'Lato', system-ui, sans-serif; font-weight: 400; }

        /* Font weight hierarchy */
        .font-display { font-weight: 900; } /* For hero titles */
        .font-heading { font-weight: 700; } /* For section headings */
        .font-subheading { font-weight: 600; } /* For subheadings */
        .font-body { font-weight: 400; } /* For body text */
        .font-light { font-weight: 300; } /* For descriptions */
        .font-thin { font-weight: 100; } /* For subtle text */

        /* Modern typography styles */
        * {
            border-color: #e5e7eb;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        button, input, textarea, select {
            font-family: 'Lato', system-ui, sans-serif;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* CSS Variables for Black and Gold Theme */
        :root {
            --color-primary: #000000;
            --color-primary-50: #f8f8f8;
            --color-primary-100: #e7e7e7;
            --color-primary-200: #d1d1d1;
            --color-primary-300: #b0b0b0;
            --color-primary-400: #888888;
            --color-primary-500: #6d6d6d;
            --color-primary-600: #5d5d5d;
            --color-primary-700: #4f4f4f;
            --color-primary-800: #454545;
            --color-primary-900: #3d3d3d;
            --color-primary-950: #000000;

            --color-secondary: #FFD700;
            --color-secondary-50: #fffef7;
            --color-secondary-100: #fffaeb;
            --color-secondary-200: #fff3c7;
            --color-secondary-300: #ffe998;
            --color-secondary-400: #ffd74f;
            --color-secondary-500: #ffc726;
            --color-secondary-600: #ffb31a;
            --color-secondary-700: #e6940f;
            --color-secondary-800: #cc7a0d;
            --color-secondary-900: #a6620a;
            --color-secondary-950: #613902;

            --color-gold: #FFD700;
        }

        /* Webkit scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--color-primary, #000000);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary-700, #4f4f4f);
        }

        /* Consistent transitions */
        a, button, input, textarea, select {
            transition: all 0.2s ease;
        }

        /* Focus styles */
        *:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--color-secondary-200, rgba(255, 215, 0, 0.3));
        }

        /* Line clamp utility */
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        /* Dynamic theme CSS */
        {!! generate_theme_css() !!}

        /* Custom black and gold design styles */
        body {
            background-color: #f8f9fa !important;
        }
        .btn-primary {
            background-color: var(--color-primary);
            color: var(--color-secondary);
            padding: 12px 24px;
            border: 2px solid var(--color-secondary);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: var(--color-secondary);
            color: var(--color-primary);
        }
        .btn-secondary {
            background-color: var(--color-secondary);
            color: var(--color-primary);
            padding: 12px 24px;
            border: 2px solid var(--color-primary);
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: var(--color-primary);
            color: var(--color-secondary);
        }
        .card-clean {
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 0;
        }
        .card-clean:hover {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            border: 1px solid var(--color-secondary);
        }
        .btn-clean {
            border-radius: 0 !important;
            background-color: var(--color-primary);
            border-color: var(--color-secondary);
            color: var(--color-secondary);
        }
        .btn-clean:hover {
            background-color: var(--color-secondary);
            border-color: var(--color-primary);
            color: var(--color-primary);
        }

        /* Slider Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-fade-in-delay-1 {
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .animate-fade-in-delay-2 {
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        .animate-fade-in-delay-3 {
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }
    </style>
</head>
<body class="{{ app()->getLocale() === 'ar' ? 'arabic rtl' : '' }} text-gray-900 antialiased" style="background-color: #f8f9fa;">
    <div id="app">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>
        
        <!-- Footer -->
        @include('layouts.footer')
    </div>
    
    <!-- Cart Sidebar -->
    @include('partials.cart-sidebar')
    
    <!-- Scripts -->
    <script>
        // Cart functionality
        window.cartData = {
            items: @json(session('cart', [])),
            total: {{ session('cart_total', 0) }},
            count: {{ session('cart_count', 0) }}
        };

        // Initialize cart count display
        document.addEventListener('DOMContentLoaded', function() {
            const cartElements = document.querySelectorAll('[x-text="window.cartData.count"], .cart-count');
            cartElements.forEach(el => el.textContent = window.cartData.count);
            refreshCartSidebar();
        });
        
        // Language switcher
        function switchLanguage(locale) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('lang', locale);
            window.location.href = currentUrl.toString();
        }
        
        // Search functionality
        function search() {
            let query = '';
            const desktopInput = document.querySelector('#search-input');
            const mobileInput = document.querySelector('#mobile-search-input');

            if (desktopInput && desktopInput.value.trim()) {
                query = desktopInput.value;
            } else if (mobileInput && mobileInput.value.trim()) {
                query = mobileInput.value;
            }

            if (query.trim()) {
                window.location.href = '{{ route('products.index') }}?search=' + encodeURIComponent(query);
            }
        }

        // Global notification function
        function showGlobalNotification(message, type = 'info', productName = '') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 w-80 rounded-xl shadow-2xl z-50 transform translate-x-full transition-all duration-500 border`;

            let bgClass, iconSvg, titleText;
            if (type === 'success') {
                bgClass = 'bg-white border-secondary';
                titleText = productName ? `Ajouté au panier` : 'Succès';
                iconSvg = `<svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                </svg>`;
            } else {
                bgClass = 'bg-white border-red-500';
                titleText = 'Erreur';
                iconSvg = `<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`;
            }

            notification.className += ` ${bgClass}`;
            notification.innerHTML = `
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">${iconSvg}</div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900">${titleText}</p>
                            <p class="mt-1 text-sm text-gray-600">${message}</p>
                            ${type === 'success' && productName ? `
                                <div class="mt-2 flex space-x-2">
                                    <a href="/panier" class="text-xs bg-secondary text-primary px-3 py-1 rounded-full font-medium hover:bg-yellow-400 transition-colors">
                                        Voir le panier
                                    </a>
                                </div>
                            ` : ''}
                        </div>
                        <button onclick="this.closest('.fixed').remove()" class="ml-4 text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);
            setTimeout(() => notification.style.transform = 'translateX(0)', 100);
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => notification.remove(), 500);
                }
            }, type === 'success' ? 5000 : 3000);
        }

        // Add to cart functionality
        function addToCart(productId, productName = '', quantity = 1) {
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update global cart data
                    window.cartData.count = data.cart_count || data.count;
                    window.cartData.total = data.cart_total || data.total;

                    // Update cart count in navbar
                    const cartElements = document.querySelectorAll('[x-text="window.cartData.count"], .cart-count');
                    cartElements.forEach(el => el.textContent = window.cartData.count);

                    // Update sidebar cart total
                    const sidebarTotal = document.getElementById('sidebar-cart-total');
                    if (sidebarTotal) {
                        sidebarTotal.textContent = '{{ site_currency() === 'EUR' ? '€' : '$' }}' + (data.cart_total || data.total || 0).toFixed(2);
                    }

                    // Refresh cart sidebar content
                    refreshCartSidebar();

                    // Dispatch cart update event
                    window.dispatchEvent(new CustomEvent('cart-updated'));

                    // Show beautiful notification
                    const message = productName ? `${productName} a été ajouté au panier avec succès!` : 'Produit ajouté au panier avec succès!';
                    showGlobalNotification(message, 'success', productName);
                } else {
                    showGlobalNotification(data.message || 'Erreur lors de l\'ajout au panier', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showGlobalNotification('Une erreur s\'est produite. Veuillez réessayer.', 'error');
            });
        }

        // Remove from cart sidebar
        function removeFromCartSidebar(productId) {
            if (!confirm('Êtes-vous sûr de vouloir retirer cet article du panier?')) {
                return;
            }

            fetch(`{{ route('cart.remove', '') }}/${productId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update global cart data
                    window.cartData.count = data.cart_count;
                    window.cartData.total = data.cart_total;

                    // Update cart count in navbar
                    const cartElements = document.querySelectorAll('[x-text="window.cartData.count"], .cart-count');
                    cartElements.forEach(el => el.textContent = window.cartData.count);

                    // Update sidebar cart total
                    const sidebarTotal = document.getElementById('sidebar-cart-total');
                    if (sidebarTotal) {
                        sidebarTotal.textContent = '{{ site_currency() === 'EUR' ? '€' : '$' }}' + (data.cart_total || 0).toFixed(2);
                    }

                    // Remove item from sidebar immediately
                    const cartItem = document.querySelector(`.cart-item[data-id="${productId}"]`);
                    if (cartItem) {
                        cartItem.style.transition = 'all 0.3s ease';
                        cartItem.style.opacity = '0';
                        cartItem.style.transform = 'translateX(20px)';
                        setTimeout(() => cartItem.remove(), 300);
                    }

                    // Refresh cart sidebar content
                    setTimeout(refreshCartSidebar, 350);

                    // Dispatch cart update event
                    window.dispatchEvent(new CustomEvent('cart-updated'));

                    showGlobalNotification('Article retiré du panier', 'success');
                } else {
                    showGlobalNotification(data.message || 'Erreur lors de la suppression', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showGlobalNotification('Une erreur s\'est produite. Veuillez réessayer.', 'error');
            });
        }

        // Refresh cart sidebar content
        function refreshCartSidebar() {
            fetch('{{ route('cart.data') }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const cartItemsContainer = document.getElementById('sidebar-cart-items');
                    if (cartItemsContainer) {
                        // Update global cart data
                        window.cartData = {
                            items: data.cart,
                            total: data.cart_total,
                            count: data.cart_count
                        };

                        // Clear current items
                        cartItemsContainer.innerHTML = '';

                        // Populate with new items
                        if (data.cart && data.cart.length > 0) {
                            let cartItemsHtml = '';
                            data.cart.forEach(item => {
                                const currency = '{{ site_currency() === 'EUR' ? '€' : '$' }}';
                                cartItemsHtml += `
                                    <div class="flex items-center space-x-4 p-4 bg-white border border-gray-100 rounded-xl hover:shadow-md transition-all duration-200 cart-item" data-id="${item.id}">
                                        <div class="flex-shrink-0">
                                            <img src="${item.image}"
                                                 alt="${item.name}"
                                                 class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 leading-tight mb-1">${item.name}</h4>
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm text-gray-600">${item.quantity} × ${currency}${parseFloat(item.price).toFixed(2)}</p>
                                                <p class="text-sm font-bold text-primary">${currency}${parseFloat(item.subtotal).toFixed(2)}</p>
                                            </div>
                                        </div>
                                        <button onclick="removeFromCartSidebar(${item.id})" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                `;
                            });
                            cartItemsContainer.innerHTML = cartItemsHtml;
                        }

                        // Update cart total
                        const sidebarTotal = document.getElementById('sidebar-cart-total');
                        if (sidebarTotal) {
                            sidebarTotal.textContent = '{{ site_currency() === 'EUR' ? '€' : '$' }}' + data.cart_total.toFixed(2);
                        }
                    }
                }
            })
            .catch(error => console.error('Error refreshing cart sidebar:', error));
        }

    </script>
    
    @stack('scripts')
</body>
</html>