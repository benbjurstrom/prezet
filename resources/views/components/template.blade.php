<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://cdn.jsdelivr.net" />
        <style type="text/css">
            /* inter-latin-400-normal */
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-display: optional;
                font-weight: 400;
                src:
                    url(https://cdn.jsdelivr.net/fontsource/fonts/inter@5.0.16/latin-400-normal.woff2)
                        format('woff2'),
                    url(https://cdn.jsdelivr.net/fontsource/fonts/inter@5.0.16/latin-400-normal.woff)
                        format('woff');
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC,
                    U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F,
                    U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215,
                    U+FEFF, U+FFFD;
            }

            /* inter-latin-500-normal */
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-display: optional;
                font-weight: 500;
                src:
                    url(https://cdn.jsdelivr.net/fontsource/fonts/inter@5.0.16/latin-500-normal.woff2)
                        format('woff2'),
                    url(https://cdn.jsdelivr.net/fontsource/fonts/inter@5.0.16/latin-500-normal.woff)
                        format('woff');
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC,
                    U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F,
                    U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215,
                    U+FEFF, U+FFFD;
            }

            /* inter-latin-600-normal */
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-display: optional;
                font-weight: 600;
                src:
                    url(https://cdn.jsdelivr.net/fontsource/fonts/inter@5.0.16/latin-600-normal.woff2)
                        format('woff2'),
                    url(https://cdn.jsdelivr.net/fontsource/fonts/inter@5.0.16/latin-600-normal.woff)
                        format('woff');
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC,
                    U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F,
                    U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215,
                    U+FEFF, U+FFFD;
            }

            /* inter-latin-700-normal */
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-display: optional;
                font-weight: 700;
                src:
                    url(https://cdn.jsdelivr.net/fontsource/fonts/inter@5.0.16/latin-700-normal.woff2)
                        format('woff2'),
                    url(https://cdn.jsdelivr.net/fontsource/fonts/inter@5.0.16/latin-700-normal.woff)
                        format('woff');
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC,
                    U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F,
                    U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215,
                    U+FEFF, U+FFFD;
            }
        </style>

        <x-seo::meta />

        <style>
            lite-youtube{background-color:#000;position:relative;display:block;contain:content;background-position:center center;background-size:cover;cursor:pointer;max-width:720px}lite-youtube::before{content:attr(data-title);display:block;position:absolute;top:0;background-image:linear-gradient(180deg,rgb(0 0 0 / 67%) 0,rgb(0 0 0 / 54%) 14%,rgb(0 0 0 / 15%) 54%,rgb(0 0 0 / 5%) 72%,rgb(0 0 0 / 0%) 94%);height:99px;width:100%;font-family:"YouTube Noto",Roboto,Arial,Helvetica,sans-serif;color:hsl(0deg 0% 93.33%);text-shadow:0 0 2px rgba(0,0,0,.5);font-size:18px;padding:25px 20px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;box-sizing:border-box}lite-youtube:hover::before{color:#fff}lite-youtube::after{content:"";display:block;padding-bottom:calc(100% / (16 / 9))}lite-youtube>iframe{width:100%;height:100%;position:absolute;top:0;left:0;border:0}lite-youtube>.lty-playbtn{display:block;width:100%;height:100%;background:no-repeat center/68px 48px;background-image:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 48"><path d="M66.52 7.74c-.78-2.93-2.49-5.41-5.42-6.19C55.79.13 34 0 34 0S12.21.13 6.9 1.55c-2.93.78-4.63 3.26-5.42 6.19C.06 13.05 0 24 0 24s.06 10.95 1.48 16.26c.78 2.93 2.49 5.41 5.42 6.19C12.21 47.87 34 48 34 48s21.79-.13 27.1-1.55c2.93-.78 4.64-3.26 5.42-6.19C67.94 34.95 68 24 68 24s-.06-10.95-1.48-16.26z" fill="red"/><path d="M45 24 27 14v20" fill="white"/></svg>');position:absolute;cursor:pointer;z-index:1;filter:grayscale(100%);transition:filter .1s cubic-bezier(0, 0, .2, 1);border:0}lite-youtube .lty-playbtn:focus,lite-youtube:hover>.lty-playbtn{filter:none}lite-youtube.lyt-activated{cursor:unset}lite-youtube.lyt-activated::before,lite-youtube.lyt-activated>.lty-playbtn{opacity:0;pointer-events:none}.lyt-visually-hidden{clip:rect(0 0 0 0);clip-path:inset(50%);height:1px;overflow:hidden;position:absolute;white-space:nowrap;width:1px}
        </style>

        <!-- Scripts -->
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/lite-youtube-embed@0.3.3/src/lite-yt-embed.min.js"
        ></script>
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.14.1/dist/cdn.min.js"
        ></script>
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"
        ></script>
        @vite(['resources/css/prezet.css'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            <x-prezet::alpine>
                <x-prezet::header />
                <div
                    class="relative mx-auto flex w-full max-w-8xl flex-auto justify-center sm:px-2 lg:px-8 xl:px-12"
                >
                    {{-- Left Sidebar --}}
                    @if (isset($left))
                        {{ $left }}
                    @endif

                    {{-- Main Content --}}
                    <main
                        class="min-w-0 max-w-2xl flex-auto px-4 py-16 lg:max-w-none lg:pl-8 lg:pr-0 xl:px-16"
                    >
                        {{ $slot }}
                    </main>

                    {{-- Right Sidebar --}}
                    @if (isset($right))
                        {{ $right }}
                    @endif
                </div>
            </x-prezet::alpine>
        </div>
    </body>
</html>
