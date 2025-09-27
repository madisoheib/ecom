<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - Laravel E-commerce' : 'Laravel E-commerce' }}</title>
    <meta name="description" content="Découvrez notre boutique en ligne avec les meilleures offres">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Lato', 'Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif'],
                        'body': ['Lato', 'Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif'],
                        'heading': ['Lato', 'Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif'],
                        'display': ['Lato', 'Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif'],
                        'arabic': ['Tajawal', 'system-ui', 'sans-serif'],
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

    <!-- Google Fonts - Lato Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    
    <style>
        [x-cloak] { display: none !important; }
        .rtl { direction: rtl; }
        .arabic { font-family: 'Tajawal', system-ui, sans-serif; }

        /* Dynamic theme CSS */
        {!! get_theme_css_variables() !!}

        /* Modern typography styles */
        * {
            border-color: #e5e7eb;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        button, input, textarea, select {
            font-family: "Lato", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight: 400;
        }

        body { font-family: "Lato", "Helvetica Neue", Helvetica, Arial, sans-serif; font-weight: 300; }
        h1, h2, h3, h4, h5, h6 { font-family: "Lato", "Helvetica Neue", Helvetica, Arial, sans-serif; }

        /* Font weight hierarchy */
        .font-display { font-family: "Lato", "Helvetica Neue", Helvetica, Arial, sans-serif; font-weight: 900; }
        .font-heading { font-family: "Lato", "Helvetica Neue", Helvetica, Arial, sans-serif; font-weight: 700; }
        .font-subheading { font-family: "Lato", "Helvetica Neue", Helvetica, Arial, sans-serif; font-weight: 500; }
        .font-body { font-family: "Lato", "Helvetica Neue", Helvetica, Arial, sans-serif; font-weight: 400; }
        .font-light { font-family: "Lato", "Helvetica Neue", Helvetica, Arial, sans-serif; font-weight: 300; }

        /* Consistent transitions */
        a, button, input, textarea, select {
            transition: all 0.2s ease;
        }
    </style>
</head>
<body class="{{ app()->getLocale() === 'ar' ? 'arabic rtl' : '' }} text-gray-900 antialiased" style="background-color: #f8f9fa;">
    <div id="app">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <main>
            <div class="min-h-screen" style="background-color: #f8f9fa;">
                {{ $slot }}
            </div>
        </main>
        
        <!-- Footer -->
        @include('layouts.footer')
    </div>
    
    <!-- Cart Sidebar -->
    @include('partials.cart-sidebar')
    
    <!-- Scripts (similar to layouts.app) -->
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
                window.location.href = '/produits?search=' + encodeURIComponent(query);
            }
        }

        // Add to cart functionality (simplified for components)
        function addToCart(productId, productName = '', quantity = 1) {
            fetch('/cart/add', {
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
                    window.cartData.count = data.cart_count || data.count;
                    const cartElements = document.querySelectorAll('[x-text="window.cartData.count"], .cart-count');
                    cartElements.forEach(el => el.textContent = window.cartData.count);
                    alert('Product added to cart!');
                } else {
                    alert(data.message || 'Failed to add product to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }

        // Refresh cart sidebar
        function refreshCartSidebar() {
            // Simplified cart sidebar refresh for components
        }
    </script>
</body>
</html>