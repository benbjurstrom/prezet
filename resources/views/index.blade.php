@php
    /* @var \BenBjurstrom\Prezet\Data\FrontmatterData $article */
@endphp

<x-prezet::template>
    @seo([
        'title' => 'Prezet: Markdown Blogging for Laravel',
        'description' =>
            'Transform your markdown files into SEO-friendly blogs, articles, and documentation!',
        'url' => route('prezet.index'),
    ])
    <x-slot name="left">
        <x-prezet::sidebar :nav="$nav" />
    </x-slot>
    <section class="mt-12">
        <div class="mb-9 space-y-1">
            <p class="font-display text-sm font-medium text-primary-600">
                Prezet
            </p>
            <h1
                class="font-display text-4xl font-medium tracking-tight text-stone-900"
            >
                Articles
            </h1>
        </div>
        <div
            class="divide-y divide-stone-100 sm:mt-4 lg:mt-8 lg:border-t lg:border-stone-100"
        >
            @foreach ($articles as $article)
                <article class="py-10 sm:py-12">
                    @if ($article->image)
                        <a href="{{ route('prezet.show', $article->slug) }}">
                            <img
                                class="mb-6 h-64 w-full max-w-xl rounded-2xl object-cover object-center"
                                src="{{ $article->image }}"
                                alt=""
                            />
                        </a>
                    @endif

                    <div>
                        <div class="lg:max-w-4xl">
                            <div
                                class="px-4 sm:px-6 md:max-w-2xl md:px-4 lg:px-0"
                            >
                                <div class="flex flex-col items-start">
                                    <h2
                                        id="episode-5-title"
                                        class="mt-2 text-lg font-bold text-stone-900"
                                    >
                                        <a
                                            href="{{ route('prezet.show', $article->slug) }}"
                                        >
                                            {{ $article->title }}
                                        </a>
                                    </h2>
                                    <time
                                        datetime="{{ $article->createdAt->toIso8601String() }}"
                                        class="order-first font-mono text-sm leading-7 text-stone-500"
                                    >
                                        {{ $article->createdAt->format('F j, Y') }}
                                    </time>
                                    <p
                                        class="mt-1 text-base leading-7 text-stone-700"
                                    >
                                        {{ $article->excerpt }}
                                    </p>
                                    <div class="mt-4 flex items-center gap-4">
                                        <a
                                            class="flex items-center text-sm font-bold leading-6 text-primary-600 hover:text-primary-600 active:text-primary-900"
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
</x-prezet::template>
