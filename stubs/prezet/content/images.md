---
title: Automatic Image Optimization
date: 2024-05-06
category: Features
excerpt: 'Prezet automatically optimizes images for the web.'
ogimage: '/prezet/img/ogimages/example.png'
---



![](images-20240509210223449.webp)

If you inspect the source code of the image above you'll see

```html
<img
    srcset="
        /prezet/img/images-20240509210223449-480w.webp   480w,
        /prezet/img/images-20240509210223449-640w.webp   640w,
        /prezet/img/images-20240509210223449-768w.webp   768w,
        /prezet/img/images-20240509210223449-960w.webp   960w,
        /prezet/img/images-20240509210223449-1536w.webp 1536w
    "
    sizes="92vw, (max-width: 1024px) 92vw, 768px"
    src="/prezet/img/images-20240509210223449.webp"
    alt=""
/>
```

## Image Optimization

// TODO
