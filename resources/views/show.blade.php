<x-app-layout>
    <div
        class="relative mx-auto flex w-full flex-auto justify-center bg-white sm:px-2 lg:px-8 xl:px-12"
        x-data="{
            activeHeading: null,
            init() {
                const headingElements = document.querySelectorAll(
                    'article h2, article h3',
                )

                // Create an Intersection Observer
                const observer = new IntersectionObserver(
                    (entries) => {
                        const visibleHeadings = entries.filter(
                            (entry) => entry.isIntersecting,
                        )
                        if (visibleHeadings.length > 0) {
                            // Find the visible heading with the lowest top value
                            const topHeading = visibleHeadings.reduce(
                                (prev, current) =>
                                    prev.boundingClientRect.top <
                                    current.boundingClientRect.top
                                        ? prev
                                        : current,
                            )

                            this.activeHeading = topHeading.target.textContent
                        }
                    },
                    { rootMargin: '0px 0px -75% 0px', threshold: 1 },
                )

                // Observe each heading
                headingElements.forEach((heading) => {
                    observer.observe(heading)
                })
            },

            scrollToHeading(headingId) {
                const heading = document.getElementById(headingId)
                if (heading) {
                    heading.scrollIntoView({ behavior: 'smooth' })
                }
            },
        }"
    >
        {{-- Left Sidebar --}}
        <div class="hidden lg:relative lg:block lg:flex-none">
            <div
                class="sticky top-[4.75rem] -ml-0.5 h-[calc(100vh-4.75rem)] w-64 overflow-y-auto overflow-x-hidden py-16 pl-0.5 pr-8 xl:w-72 xl:pr-16"
            >
                <nav class="text-base lg:text-sm">
                    <ul role="list" class="space-y-9">
                        @foreach ($nav as $section)
                            <li>
                                <h2
                                    class="font-display font-medium text-slate-900"
                                >
                                    {{ $section['title'] }}
                                </h2>
                                <ul
                                    role="list"
                                    class="mt-2 space-y-2 border-l-2 border-slate-100 lg:mt-4 lg:space-y-4 lg:border-slate-200"
                                >
                                    @foreach ($section['links'] as $link)
                                        <li class="relative">
                                            <a
                                                @class([
                                                    'block w-full pl-3.5 before:pointer-events-none before:absolute before:-left-1 before:top-1/2 before:h-1.5 before:w-1.5 before:-translate-y-1/2 before:rounded-full',
                                                    'font-semibold text-primary-500 before:bg-primary-500' =>
                                                        url()->current() === route('prezet.show', ['slug' => $link['slug']]),
                                                    'text-slate-500 before:hidden before:bg-slate-300 hover:text-slate-600 hover:before:block' =>
                                                        url()->current() !== route('prezet.show', ['slug' => $link['slug']]),
                                                ])
                                                href="{{ route('prezet.show', ['slug' => $link['slug']]) }}"
                                            >
                                                {{ $link['title'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>
        {{-- Main Content --}}
        <div class="min-w-0 max-w-4xl flex-auto">
            <div class="sticky top-0 lg:hidden">
                <div
                    class="flex h-16 w-full items-center border-b border-slate-200 bg-slate-100 px-4"
                >
                    Table of Contents
                </div>
            </div>
            <div class="px-4 py-16 lg:pl-8 lg:pr-0 xl:px-16">
                <article>
                    <header class="mb-9 space-y-1">
                        <p
                            class="font-display text-sm font-medium text-primary-500"
                        >
                            Core concepts
                        </p>
                        <h1
                            class="font-display text-3xl tracking-tight text-slate-900"
                        >
                            {{ $article->title }}
                        </h1>
                    </header>
                    <div
                        class="prose-headings:font-display prose prose-slate max-w-none prose-a:border-b prose-a:border-dashed prose-a:border-black/30 prose-a:font-semibold prose-a:no-underline hover:prose-a:border-solid prose-img:rounded"
                    >
                        {!! $body !!}
                    </div>
                </article>
                <dl class="mt-12 flex border-t border-slate-200 pt-6">
                    <div>
                        <dt
                            class="font-display text-sm font-medium text-slate-900"
                        >
                            Previous
                        </dt>
                        <dd class="mt-1">
                            <a
                                class="flex flex-row-reverse items-center gap-x-1 text-base font-semibold text-slate-500 hover:text-slate-600"
                                href="/docs/installation"
                            >
                                Installation
                                <svg
                                    viewBox="0 0 16 16"
                                    aria-hidden="true"
                                    class="h-4 w-4 flex-none -scale-x-100 fill-current"
                                >
                                    <path
                                        d="m9.182 13.423-1.17-1.16 3.505-3.505H3V7.065h8.517l-3.506-3.5L9.181 2.4l5.512 5.511-5.511 5.512Z"
                                    ></path>
                                </svg>
                            </a>
                        </dd>
                    </div>
                    <div class="ml-auto text-right">
                        <dt
                            class="font-display text-sm font-medium text-slate-900"
                        >
                            Next
                        </dt>
                        <dd class="mt-1">
                            <a
                                class="flex items-center gap-x-1 text-base font-semibold text-slate-500 hover:text-slate-600"
                                href="/docs/predicting-user-behavior"
                            >
                                Predicting user behavior
                                <svg
                                    viewBox="0 0 16 16"
                                    aria-hidden="true"
                                    class="h-4 w-4 flex-none fill-current"
                                >
                                    <path
                                        d="m9.182 13.423-1.17-1.16 3.505-3.505H3V7.065h8.517l-3.506-3.5L9.181 2.4l5.512 5.511-5.511 5.512Z"
                                    ></path>
                                </svg>
                            </a>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        {{-- Right Sidebar --}}
        <div class="hidden lg:relative lg:block lg:flex-none">
            <div
                class="hidden xl:sticky xl:top-[4.75rem] xl:-mr-6 xl:block xl:h-[calc(100vh-4.75rem)] xl:flex-none xl:overflow-y-auto xl:py-16 xl:pr-6"
            >
                <nav aria-labelledby="on-this-page-title" class="w-56">
                    <h2
                        id="on-this-page-title"
                        class="font-display text-sm font-medium text-slate-900"
                    >
                        On this page
                    </h2>
                    <ol
                        role="list"
                        class="mt-4 space-y-3 text-sm font-normal text-slate-500 hover:text-slate-700"
                    >
                        @foreach ($headings as $h2)
                            <li>
                                <h3>
                                    <a
                                        href="#{{ $h2['id'] }}"
                                        :class="{'text-primary-500 hover:text-primary-500': activeHeading === '#{{ $h2['title'] }}'}"
                                        x-on:click.prevent="scrollToHeading('{{ $h2['id'] }}')"
                                        class="transition-colors"
                                    >
                                        {{ $h2['title'] }}
                                    </a>
                                </h3>

                                @if ($h2['children'])
                                    <ol
                                        role="list"
                                        class="mt-2 space-y-3 border-l pl-5"
                                    >
                                        @foreach ($h2['children'] as $h3)
                                            <li>
                                                <a
                                                    href="#{{ $h3['id'] }}"
                                                    :class="{'text-primary-500 hover:text-primary-500': activeHeading === '#{{ $h3['title'] }}'}"
                                                    x-on:click.prevent="scrollToHeading('{{ $h3['id'] }}')"
                                                    class="transition-colors"
                                                >
                                                    {{ $h3['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ol>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</x-app-layout>
