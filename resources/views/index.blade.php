@php
    /* @var \BenBjurstrom\Prezet\Data\FrontmatterData $article */
    $currentTag = request()->query('tag');
    $currentCategory = request()->query('category');
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
        <div class="mb-9 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-display text-sm font-medium text-primary-600 uppercase tracking-wider">
                        Prezet Blog
                    </p>
                    <div class="flex">
                        <h1 class="font-display text-4xl font-bold tracking-tight text-stone-900 mt-2">
                            Articles
                        </h1>
                    </div>
                </div>
                <div class="block">
                    @if($currentTag)
                        <span class="inline-flex items-center gap-x-0.5 rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                {{ $currentTag }}
                              <a href="{{ route('prezet.index', array_filter(request()->except('tag'))) }}" class="group relative -mr-1 h-3.5 w-3.5 rounded-sm hover:bg-gray-500/20">
                                <span class="sr-only">Remove</span>
                                <svg viewBox="0 0 14 14" class="h-3.5 w-3.5 stroke-gray-600/50 group-hover:stroke-gray-600/75">
                                  <path d="M4 4l6 6m0-6l-6 6" />
                                </svg>
                                <span class="absolute -inset-1"></span>
                              </a>
                        </span>
                    @endif
                    @if($currentCategory)
                            <span class="inline-flex items-center gap-x-0.5 rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                {{ $currentCategory }}
                              <a href="{{ route('prezet.index', array_filter(request()->except('category'))) }}" class="group relative -mr-1 h-3.5 w-3.5 rounded-sm hover:bg-gray-500/20">
                                <span class="sr-only">Remove</span>
                                <svg viewBox="0 0 14 14" class="h-3.5 w-3.5 stroke-gray-600/50 group-hover:stroke-gray-600/75">
                                  <path d="M4 4l6 6m0-6l-6 6" />
                                </svg>
                                <span class="absolute -inset-1"></span>
                              </a>
                        </span>
                    @endif
                </div>
            </div>
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
                                    @if($article->category)
                                        <span class="text-sm text-primary-600">
                                                <a href="{{ route('prezet.index', ['category' => $article->category]) }}">
                                                    {{ $article->category }}
                                                </a>
                                            </span>
                                    @endif
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
                                    <div class="flex items-center space-x-2">
                                        <time
                                            datetime="{{ $article->createdAt->toIso8601String() }}"
                                            class="order-first font-mono text-sm leading-7 text-stone-500"
                                        >
                                            {{ $article->createdAt->format('F j, Y') }}
                                        </time>
                                    </div>
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
                                    @if(count($article->tags) > 0)
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach($article->tags as $tag)
                                                <a href="{{ route('prezet.index', ['tag' => $tag]) }}" class="text-xs bg-gray-200 rounded-full px-2 py-1 text-gray-700 hover:bg-gray-300">
                                                    {{ $tag }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</x-prezet::template>
