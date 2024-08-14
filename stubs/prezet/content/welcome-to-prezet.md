---
title: "Welcome to Prezet"
date: 2024-07-03
category: "Getting Started"
excerpt: "An introduction to Prezet and its key features."
image: /prezet/img/images-20240509210223449-768w.webp
tags:   
    - markdown
---

Prezet is a powerful markdown blogging package for Laravel. This post will introduce you to some of its key features. For comprehensive documentation, visit [prezet.com](https://prezet.com).

## Embedding Images

Prezet makes it easy to embed and optimize images. Here's an example:

![An embeded image](images-20240509210223449.webp)

Learn more about [Optimized Images](https://prezet.com/features/images) on prezet.com.

## Using Blade Components

Prezet allows you to use Blade components directly in your markdown. Here's an example blade component for an alert:

```html +parse
<x-prezet::alert
    type="warning"
    title="Alert component"
    body="This is a Laravel Blade component rendered from markdown."
/>
```

And here is an example blade component that embeds a YouTube video:

```html +parse
<x-prezet::youtube
    videoid="dt1ado9wJi8"
    title="Supercharge Markdown with Laravel"
    date="2023-12-15T00:00:00+08:00"
/>
```

See the full documentation for more on using [Blade Components](https://prezet.com/features/blade) in Prezet.

## Markdown Support

Prezet supports all standard markdown features:

-   **Bold text**
-   _Italic text_
-   ~~Strikethrough~~
-   [Links](https://example.com)
-   And more!

For a deep dive into Prezet's markdown capabilities, check out the [Markdown Powered](https://prezet.com/features/markdown) article.
