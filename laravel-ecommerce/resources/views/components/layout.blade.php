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
    
    <!-- Arabic Styles -->
    <link href="{{ asset('css/arabic-styles.css') }}" rel="stylesheet">

    <style>
        /* Dynamic theme CSS */
        {!! get_theme_css_variables() !!}
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
    
    <!-- Layout JavaScript -->
    <script src="{{ asset('js/layout.js') }}?v={{ time() }}"></script>

    <!-- Initialize cart data from server -->
    <script>
        // Initialize cart data from Laravel session
        window.cartData = {
            items: @json(session('cart', [])),
            total: {{ session('cart_total', 0) }},
            count: {{ session('cart_count', 0) }}
        };

        // Initialize cart routes for JavaScript
        window.cartRoutes = {
            add: '{{ route('cart.add') }}',
            update: '{{ url('panier/modifier') }}',
            remove: '{{ url('panier/supprimer') }}',
            clear: '{{ route('cart.clear') }}',
            data: '{{ route('cart.data') }}'
        };
    </script>
</body>
</html>