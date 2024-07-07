@php
    $ytData = new \BenBjurstrom\Prezet\Data\YoutubeData($attributes->all());
@endphp

<div class="aspect-video" {{ $attributes }}>
    <lite-youtube
        videoid="{{ $attributes['videoid'] }}"
        style="background-image: url('https://i.ytimg.com/vi/{{ $attributes['videoid'] }}/hqdefault.jpg');"
        title="{{ $attributes['title'] }}"
    >
        <a href="https://youtube.com/watch?v={{ $attributes['videoid'] }}" class="lty-playbtn" title="Play Video">
            <span class="lyt-visually-hidden">Play Video: {{ $attributes['title'] }}</span>
        </a>
    </lite-youtube>

    <script
        type="application/ld+json"
    >
        {!! json_encode($ytData->toArray(), JSON_UNESCAPED_SLASHES) !!}
    </script>
</div>
