@if(seo('title'))
    <title>@seo('title')</title>

    @unless(seo()->hasTag('og:title'))
        {{-- If an og:title tag is provided directly, it's included in the @foreach below --}}
        <meta property="og:title" content="@seo('title')">
    @endunless
@endif

@if(seo('description'))
    <meta property="og:description" content="@seo('description')">
    <meta name="description" content="@seo('description')">
@endif

@if(seo('keywords'))
    <meta name="keywords" content="@seo('keywords')">
@endif

@if(seo('type'))
    <meta property="og:type" content="@seo('type')">
@else
    <meta property="og:type" content="website">
@endif

@if(seo('site')) <meta property="og:site_name" content="@seo('site')"> @endif

@if(seo('locale')) <meta property="og:locale" content="@seo('locale')"> @endif

@if(seo('image')) <meta property="og:image" content="@seo('image')"> @endif

@if(seo('url'))
    <meta property="og:url" content="@seo('url')">
    <link rel="canonical" href="@seo('url')">
@endif

@foreach(seo()->tags() as $tag)
    {!! $tag !!}
@endforeach

@if(seo('twitter.title'))<meta name="twitter:card" content="summary_large_image">@endif
@if(seo('twitter.creator')) <meta name="twitter:creator" content="@seo('twitter.creator')"> @endif
@if(seo('twitter.site')) <meta name="twitter:site" content="@seo('twitter.site')"> @endif
@if(seo('twitter.title')) <meta name="twitter:title" content="@seo('twitter.title')"> @endif
@if(seo('twitter.description')) <meta name="twitter:description" content="@seo('twitter.description')"> @endif
@if(seo('twitter.image')) <meta name="twitter:image" content="@seo('twitter.image')"> @endif
