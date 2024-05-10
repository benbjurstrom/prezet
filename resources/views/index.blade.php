@php
    /* @var \BenBjurstrom\Prezet\Data\FrontmatterData $article */
@endphp

<x-prezet::layout>
    <div
        class="relative mx-auto flex w-full max-w-8xl flex-auto justify-center sm:px-2 lg:px-8 xl:px-12"
    >
        {{-- Left Sidebar --}}
        <div class="hidden lg:relative lg:block lg:flex-none">
            <div class="absolute inset-y-0 right-0 w-[50vw] bg-slate-50"></div>
            <div
                class="absolute bottom-0 right-0 top-16 hidden h-12 w-px bg-gradient-to-t from-slate-800"
            ></div>
            <div
                class="absolute bottom-0 right-0 top-28 hidden w-px bg-slate-800"
            ></div>
            <div class="h-[calc(100vh-4.75rem)] w-64 xl:w-72"></div>
        </div>

        {{-- Main Content --}}
        <div
            class="min-w-0 max-w-2xl flex-auto px-4 py-16 lg:max-w-none lg:pl-8 lg:pr-0 xl:px-16"
        >
            <section class="mt-12">
                <header class="mb-9 space-y-1">
                    <p
                        class="font-display text-sm font-medium text-primary-500"
                    >
                        Prezet
                    </p>
                    <h1
                        class="font-display text-4xl font-medium tracking-tight text-slate-900"
                    >
                        Articles
                    </h1>
                </header>
                <div
                    class="divide-y divide-slate-100 sm:mt-4 lg:mt-8 lg:border-t lg:border-slate-100"
                >
                    @foreach ($articles as $article)
                        <article class="py-10 sm:py-12">
                            <div>
                                <div class="lg:max-w-4xl">
                                    <div
                                        class="px-4 sm:px-6 md:max-w-2xl md:px-4 lg:px-0"
                                    >
                                        <div class="flex flex-col items-start">
                                            <h2
                                                id="episode-5-title"
                                                class="mt-2 text-lg font-bold text-slate-900"
                                            >
                                                <a
                                                    href="{{ route('prezet.show', $article->slug) }}"
                                                >
                                                    {{ $article->title }}
                                                </a>
                                            </h2>
                                            <time
                                                datetime="2022-02-24T00:00:00.000Z"
                                                class="order-first font-mono text-sm leading-7 text-slate-500"
                                            >
                                                {{ $article->date->format('F j, Y') }}
                                            </time>
                                            <p
                                                class="mt-1 text-base leading-7 text-slate-700"
                                            >
                                                {{ $article->excerpt }}
                                            </p>
                                            <div
                                                class="mt-4 flex items-center gap-4"
                                            >
                                                <a
                                                    class="flex items-center text-sm font-bold leading-6 text-primary-500 hover:text-primary-700 active:text-primary-900"
                                                    href="{{ route('prezet.show', $article->slug) }}"
                                                >
                                                    Read more
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</x-prezet::layout>
