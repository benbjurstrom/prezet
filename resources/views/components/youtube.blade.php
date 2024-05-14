<div class="aspect-video" {{ $attributes }}>
    <iframe
        class="h-full w-full"
        src="https://www.youtube-nocookie.com/embed/{{ $attributes['videoid'] }}"
        width="100%"
        title="{{ $attributes['title'] }}"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
    ></iframe>

    <script
        type="application/ld+json"
    >
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'url' => 'https://www.youtube.com/watch?v=' . $attributes['title'],
            'name' => $attributes['title'],
            'identifier' => $attributes['videoid'],
            'description' => $attributes['description'],
            'thumbnailUrl' => 'https://i.ytimg.com/vi/' . $attributes['videoid'] . '/maxresdefault.jpg',
            'uploadDate' => $attributes['date'],
            'duration' => $attributes['duration'],
            'embedUrl' => 'https://www.youtube.com/embed/' . $attributes['videoid'],
        ], JSON_UNESCAPED_SLASHES) !!}
    </script>
</div>
