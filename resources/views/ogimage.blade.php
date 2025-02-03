@php
    /* @var \BenBjurstrom\Prezet\Data\FrontmatterData $fm */
@endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="robots" content="noindex" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link
        href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap"
        rel="stylesheet"
    />

    <!-- Scripts -->
    @vite(['resources/css/prezet.css'])
</head>
<body
    class="font-sans text-gray-900 antialiased"
    style="height: 630px; width: 1200px"
>
<div
    class="relative flex flex-col h-full w-full justify-between bg-stone-100 p-20"
>
    <div></div>
    <h1 class="font-bold inline text-stone-800 text-8xl tracking-tight py-4 px-12">
        {{ $fm->title }}
    </h1>
    <div class="flex justify-end">
        <div class="w-16">
            <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 29">
                <path d="M0 27 7 1h5L5 27H0ZM13 10V5l13 6.5v5L13 23v-5l8-4-8-4Z" fill="#292524"/>
            </svg>
        </div>
    </div>
</div>
</body>
</html>
