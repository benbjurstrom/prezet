---
title: "This post highlights some of Prezet's features"
date: 2024-05-08
category: Features
excerpt: 'This post contains a variety of markdown features that Prezet supports.'
---

This post contains a variety of markdown features that Prezet supports.

## Table of Contents

If you're on desktop, you should see a table of contents on the right side of the page. This is automatically generated based on the headings in the markdown file.

## Optimized Images

![](example-20240509210223449.webp)

If you inspect the source code of the image above you'll see

```html
<img
    srcset="
        /prezet/img/example-20240509210223449-480w.webp   480w,
        /prezet/img/example-20240509210223449-640w.webp   640w,
        /prezet/img/example-20240509210223449-768w.webp   768w,
        /prezet/img/example-20240509210223449-960w.webp   960w,
        /prezet/img/example-20240509210223449-1536w.webp 1536w
    "
    sizes="92vw, (max-width: 1024px) 92vw, 768px"
    src="/prezet/img/example-20240509210223449.webp"
    alt=""
/>
```

## OgImages

Open graph images

```html +parse
<x-prezet::meta></x-prezet::meta>
```
