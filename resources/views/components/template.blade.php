<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=Inter:400,500,600,700&display=swap"
            rel="stylesheet"
        />

        <x-seo::meta />

        <!-- Scripts -->
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
        ></script>
        @vite(['resources/css/app.css'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen" x-data="{ showSidebar: false }">
            <x-prezet::header />
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
