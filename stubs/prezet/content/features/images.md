---
title: Automatic Image Optimization
date: 2024-05-06
category: Features
excerpt: Prezet automatically optimizes images in your markdown files for responsive, efficient loading. Learn how it works and how to customize the settings.
image: /prezet/img/ogimages/features-images.webp
---

Prezet offers image optimization capabilities out of the box, enhancing your markdown-based blog with responsive, efficiently-loaded images like the one below.

![](images-20240509210223449.webp)

## How It Works

When you include a locally referenced image in your markdown file, Prezet's custom CommonMark extension, `MarkdownImageExtension`, transforms the standard image tag into a responsive, optimized version.

For example, if your markdown contains:

```markdown
![](images-20240509210223449.webp)
```

Prezet will convert this into an HTML tag with responsive image attributes:

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

## Image Controller

You'll notice that the `src` and `srcset` attributes have been modified to point to a package provided route contained in the `routes/prezet.php` file.

```php

<?php

use BenBjurstrom\Prezet\Http\Controllers\ImageController;
...
use Illuminate\Support\Facades\Route;

Route::get('prezet/img/{path}', ImageController::class)
    ->name('prezet.image')
    ->where('path', '.*');

...
```


The package's ImageController serves the optimized image based on the requested width and format. 

For example, when a browser requests `/prezet/img/images-20240509210223449-480w.webp`, the controller generates and returns a 480px wide WebP image from the original image file.

This approach ensures that your blog images are served in the most efficient format and size for the user's device, network conditions, and layout requirements.


## The `srcset` Attribute

The `srcset` attribute provides the browser with a list of image sources and their respective widths, allowing the browser to choose the most appropriate image based on the device's characteristics and the current layout.

In the example above:

```html
srcset="
    /prezet/img/images-20240509210223449-480w.webp   480w,
    /prezet/img/images-20240509210223449-640w.webp   640w,
    /prezet/img/images-20240509210223449-768w.webp   768w,
    /prezet/img/images-20240509210223449-960w.webp   960w,
    /prezet/img/images-20240509210223449-1536w.webp 1536w
"
```

Each entry in the `srcset` consists of two parts:
1. The URL of the image source
2. The intrinsic width of the image in pixels, denoted by the `w` unit

The browser uses this information, along with the `sizes` attribute and the device's characteristics (like screen resolution and pixel density), to determine which image to download and display.

By leveraging the `srcset` attribute, Prezet enables your blog to serve the most appropriate image for each user's device and viewing context; balancing performance and visual quality.

## The `sizes` Attribute

The `sizes` attribute tells the browser how wide the image will be displayed at different viewport sizes. 

In the example above:

```html
sizes="92vw, (max-width: 1024px) 92vw, 768px"
```

Tells the browser that:
- For viewports up to 1024px wide, the image will occupy 92% of the viewport width.
- For viewports wider than 1024px, the image will be 768px wide.

This information helps the browser choose the most appropriate image size from the `srcset`. For a deeper understanding of the `sizes` attribute, refer to [The Definitive Guide to Responsive Images on the Web](https://coderpad.io/blog/development/the-definitive-guide-to-responsive-images-on-the-web/#:~:text=Adding%20the%20sizes%20attribute).

## Configuration

You can customize the image optimization settings in the `config/prezet.php` file:

```php
'image' => [
    'widths' => [
        480, 640, 768, 960, 1536,
    ],
    'sizes' => '92vw, (max-width: 1024px) 92vw, 768px',
],
```

- `widths`: An array of image widths to generate for the `srcset`.
- `sizes`: The `sizes` attribute to be added to the image tag.

## Disabling Image Optimization

If you wish to disable the automatic image optimization, you can remove the `BenBjurstrom\Prezet\Extensions\MarkdownImageExtension::class` from the `extensions` array in the `commonmark` section of your `config/prezet.php` file.
