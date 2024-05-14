<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <meta name="robots" content="noindex" inertia="robots" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap"
            rel="stylesheet"
        />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="font-sans text-gray-900 antialiased"
        style="height: 630px; width: 1200px"
    >
        <div
            class="relative flex h-full w-full items-start justify-start bg-yellow-50"
        >
            <h1 class="z-10 p-20 text-left text-8xl font-bold text-slate-200">
                {{ $title }}
            </h1>

            {{-- https://app.haikei.app/ --}}
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 1200 630"
                class="absolute bottom-0 left-0 right-0"
            >
                <path class="fill-slate-950" d="M0 0h1200v630H0z" />
                <path
                    d="m0 432 28.5-4.2c28.5-4.1 85.5-12.5 142.7-13.5 57.1-1 114.5 5.4 171.6-.5C400 408 457 390 514.2 386.3c57.1-3.6 114.5 7 171.6 6.4C743 392 800 380 857.2 384.3c57.1 4.4 114.5 25 171.6 31.7 57.2 6.7 114.2-.7 142.7-4.3l28.5-3.7v223H0Z"
                    class="fill-slate-200"
                />
                <path
                    d="m0 413 28.5 2.7c28.5 2.6 85.5 8 142.7 12.6 57.1 4.7 114.5 8.7 171.6 18.5 57.2 9.9 114.2 25.5 171.4 23.9 57.1-1.7 114.5-20.7 171.6-19.5 57.2 1.1 114.2 22.5 171.4 33.5 57.1 11 114.5 11.6 171.6-2C1086 469 1143 441 1171.5 427l28.5-14v218H0Z"
                    class="fill-slate-300"
                />
                <path
                    d="m0 512 28.5-4.2c28.5-4.1 85.5-12.5 142.7-14.3 57.1-1.8 114.5 2.8 171.6 4.3 57.2 1.5 114.2-.1 171.4-4.1 57.1-4 114.5-10.4 171.6-5C743 494 800 511 857.2 510.5c57.1-.5 114.5-18.5 171.6-28.3 57.2-9.9 114.2-11.5 142.7-12.4l28.5-.8v162H0Z"
                    class="fill-slate-400"
                />
                <path
                    d="m0 529 28.5 6.7c28.5 6.6 85.5 20 142.7 24 57.1 4 114.5-1.4 171.6-11.9 57.2-10.5 114.2-26.1 171.4-27.1 57.1-1 114.5 12.6 171.6 20.8 57.2 8.2 114.2 10.8 171.4 12.7 57.1 1.8 114.5 2.8 171.6-1.7C1086 548 1143 538 1171.5 533l28.5-5v103H0Z"
                    class="fill-slate-500"
                />
                <path
                    d="m0 552 28.5 3.5C57 559 114 566 171.2 566.5c57.1.5 114.5-5.5 171.6-1C400 570 457 585 514.2 585.8c57.1.9 114.5-12.5 171.6-17.5 57.2-5 114.2-1.6 171.4 2.9 57.1 4.5 114.5 10.1 171.6 8C1086 577 1143 567 1171.5 562l28.5-5v74H0Z"
                    class="fill-slate-600"
                />
            </svg>
        </div>
    </body>
</html>
