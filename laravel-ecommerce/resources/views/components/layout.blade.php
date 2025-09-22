<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' . site_title() : site_title() }}</title>

    @if(site_meta_description())
    <meta name="description" content="{{ site_meta_description() }}">
    @endif

    @if(site_meta_keywords())
    <meta name="keywords" content="{{ site_meta_keywords() }}">
    @endif

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: {{ site_primary_color() }};
            --secondary-color: {{ site_secondary_color() }};
            --accent-color: {{ site_accent_color() }};
        }

        .bg-primary { background-color: var(--primary-color); }
        .text-primary { color: var(--primary-color); }
        .border-primary { border-color: var(--primary-color); }

        .bg-secondary { background-color: var(--secondary-color); }
        .text-secondary { color: var(--secondary-color); }
        .border-secondary { border-color: var(--secondary-color); }

        .bg-accent { background-color: var(--accent-color); }
        .text-accent { color: var(--accent-color); }
        .border-accent { border-color: var(--accent-color); }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            @if(site_logo())
                                <img class="block h-9 w-auto" src="{{ Storage::url(site_logo()) }}" alt="{{ site_title() }}">
                            @else
                                <span class="text-xl font-semibold text-primary">{{ site_title() }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <!-- Add your navigation links here -->
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-12">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p>&copy; {{ date('Y') }} {{ site_title() }}. All rights reserved.</p>
                    <p class="text-gray-400 text-sm mt-2">Default Currency: {{ site_currency() }}</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>