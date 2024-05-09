@php
    /* @var \BenBjurstrom\Prezet\Data\FrontmatterData $posts */
@endphp

<x-app-layout>
    <ul>
        @foreach ($posts as $post)
            <li>
                <a href="{{ route('prezet.show', $post->slug) }}">
                    {{ $post->title }}
                </a>
            </li>
        @endforeach
    </ul>
</x-app-layout>
