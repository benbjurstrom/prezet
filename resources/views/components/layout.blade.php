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
        <div class="min-h-screen">
            <main>
                <header
                    class="sticky top-0 z-50 flex flex-none flex-wrap items-center justify-between bg-white px-4 py-5 shadow-md shadow-slate-900/5 transition duration-500 sm:px-6 lg:px-8"
                >
                    <div class="relative flex flex-grow basis-0 items-center">
                        <a href="{{ route('prezet.index') }}">
                            <svg
                                class="h-9 w-auto fill-slate-400"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 101 36"
                            >
                                <path
                                    d="m5 31 7-26h5l-7 26H5ZM18 14V9l13 6.5v5L18 27v-5l8-4-8-4ZM36.011 24V12.364h4.591c.883 0 1.635.168 2.256.505a3.424 3.424 0 0 1 1.42 1.392c.33.591.495 1.273.495 2.046 0 .773-.167 1.454-.5 2.045a3.449 3.449 0 0 1-1.45 1.381c-.628.33-1.39.494-2.283.494h-2.926v-1.971h2.528c.473 0 .864-.082 1.17-.245a1.62 1.62 0 0 0 .694-.687c.155-.296.233-.635.233-1.017 0-.386-.078-.724-.233-1.011a1.556 1.556 0 0 0-.694-.677c-.31-.163-.704-.244-1.181-.244h-1.66V24h-2.46Zm10.36 0V12.364h4.59c.88 0 1.63.157 2.25.471.626.31 1.101.752 1.427 1.324.33.568.494 1.237.494 2.006 0 .773-.167 1.437-.5 1.994-.333.553-.816.977-1.449 1.273-.629.295-1.39.443-2.284.443h-3.074v-1.977h2.676c.47 0 .86-.065 1.17-.194.312-.128.543-.321.694-.579.155-.258.233-.578.233-.96 0-.387-.078-.712-.233-.977a1.429 1.429 0 0 0-.699-.603c-.31-.14-.702-.21-1.176-.21h-1.659V24h-2.46Zm6.284-5.296L55.547 24H52.83l-2.83-5.296h2.654ZM56.87 24V12.364h7.84v2.028h-5.38v2.773h4.977v2.028h-4.977v2.779h5.403V24h-7.863Zm9.672 0v-1.46l5.806-8.148h-5.818v-2.028h8.91v1.46l-5.813 8.148h5.824V24h-8.91Zm10.796 0V12.364h7.841v2.028H79.8v2.773h4.977v2.028H79.8v2.779h5.403V24H77.34Zm9.354-9.608v-2.028h9.557v2.028h-3.563V24h-2.431v-9.608h-3.563Z"
                                />
                            </svg>
                        </a>
                    </div>
                    <div
                        class="relative flex basis-0 justify-end gap-6 sm:gap-8 md:flex-grow"
                    >
                        <a
                            class="group"
                            aria-label="GitHub"
                            href="https://github.com/benbjurstrom/prezet"
                            target="_blank"
                        >
                            <svg
                                aria-hidden="true"
                                viewBox="0 0 16 16"
                                class="h-6 w-6 fill-slate-400 group-hover:fill-slate-500"
                            >
                                <path
                                    d="M8 0C3.58 0 0 3.58 0 8C0 11.54 2.29 14.53 5.47 15.59C5.87 15.66 6.02 15.42 6.02 15.21C6.02 15.02 6.01 14.39 6.01 13.72C4 14.09 3.48 13.23 3.32 12.78C3.23 12.55 2.84 11.84 2.5 11.65C2.22 11.5 1.82 11.13 2.49 11.12C3.12 11.11 3.57 11.7 3.72 11.94C4.44 13.15 5.59 12.81 6.05 12.6C6.12 12.08 6.33 11.73 6.56 11.53C4.78 11.33 2.92 10.64 2.92 7.58C2.92 6.71 3.23 5.99 3.74 5.43C3.66 5.23 3.38 4.41 3.82 3.31C3.82 3.31 4.49 3.1 6.02 4.13C6.66 3.95 7.34 3.86 8.02 3.86C8.7 3.86 9.38 3.95 10.02 4.13C11.55 3.09 12.22 3.31 12.22 3.31C12.66 4.41 12.38 5.23 12.3 5.43C12.81 5.99 13.12 6.7 13.12 7.58C13.12 10.65 11.25 11.33 9.47 11.53C9.76 11.78 10.01 12.26 10.01 13.01C10.01 14.08 10 14.94 10 15.21C10 15.42 10.15 15.67 10.55 15.59C13.71 14.53 16 11.53 16 8C16 3.58 12.42 0 8 0Z"
                                ></path>
                            </svg>
                        </a>
                    </div>
                </header>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
