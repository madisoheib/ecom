<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(isset($meta))
        <title>{{ $meta['title'] ?? (isset($title) ? $title . ' - Laravel E-commerce' : 'Laravel E-commerce') }}</title>
        <meta name="description" content="{{ $meta['description'] ?? 'Découvrez notre boutique en ligne avec les meilleures offres' }}">
        @if(isset($meta['keywords']))
            <meta name="keywords" content="{{ $meta['keywords'] }}">
        @endif
        @if(isset($meta['canonical']))
            <link rel="canonical" href="{{ $meta['canonical'] }}">
        @endif
        @if(isset($meta['robots']))
            <meta name="robots" content="{{ $meta['robots'] }}">
        @endif

        <!-- Open Graph -->
        <meta property="og:title" content="{{ $meta['og:title'] ?? $meta['title'] ?? 'Laravel E-commerce' }}">
        <meta property="og:description" content="{{ $meta['og:description'] ?? $meta['description'] ?? 'Découvrez notre boutique en ligne' }}">
        <meta property="og:type" content="{{ $meta['og:type'] ?? 'website' }}">
        <meta property="og:url" content="{{ $meta['og:url'] ?? url()->current() }}">
        @if(isset($meta['og:image']))
            <meta property="og:image" content="{{ $meta['og:image'] }}">
        @endif
        <meta property="og:site_name" content="{{ config('app.name') }}">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="{{ $meta['twitter:card'] ?? 'summary_large_image' }}">
        <meta name="twitter:title" content="{{ $meta['twitter:title'] ?? $meta['title'] ?? 'Laravel E-commerce' }}">
        <meta name="twitter:description" content="{{ $meta['twitter:description'] ?? $meta['description'] ?? 'Découvrez notre boutique en ligne' }}">
        @if(isset($meta['twitter:image']))
            <meta name="twitter:image" content="{{ $meta['twitter:image'] }}">
        @endif

        <!-- Product-specific meta tags -->
        @if(isset($meta['product:brand']))
            <meta property="product:brand" content="{{ $meta['product:brand'] }}">
        @endif
        @if(isset($meta['product:retailer_item_id']))
            <meta property="product:retailer_item_id" content="{{ $meta['product:retailer_item_id'] }}">
        @endif
        @if(isset($meta['product:price:amount']))
            <meta property="product:price:amount" content="{{ $meta['product:price:amount'] }}">
            <meta property="product:price:currency" content="{{ $meta['product:price:currency'] ?? 'EUR' }}">
        @endif
        @if(isset($meta['product:availability']))
            <meta property="product:availability" content="{{ $meta['product:availability'] }}">
        @endif

        <!-- Hreflang tags for multilingual SEO -->
        @if(isset($hreflangTags))
            {!! $hreflangTags !!}
        @endif
    @else
        <title>{{ isset($title) ? $title . ' - Laravel E-commerce' : 'Laravel E-commerce' }}</title>
        <meta name="description" content="Découvrez notre boutique en ligne avec les meilleures offres">
    @endif

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

    <!-- shadcn/ui styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@radix-ui/themes@2.0.0/styles.css">

    <!-- Inter font for shadcn -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Google Fonts - Lato Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <!-- Heroicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/heroicons@2.0.18/24/outline/style.css">

    <!-- App Styles -->
    <link href="{{ asset('css/app-styles.css') }}" rel="stylesheet">

    <style>
        /* Dynamic theme CSS - Inline due to PHP variable dependency */
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
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        @include('layouts.footer')
    </div>

    <!-- Cart Sidebar -->
    @include('partials.cart-sidebar')

    <!-- App JavaScript -->
    <script src="{{ asset('js/app.js') }}?v={{ time() }}"></script>

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