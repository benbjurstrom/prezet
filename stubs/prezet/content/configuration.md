---
title: Understanding Prezet's Configuration File
excerpt: Learn how to install and customize the configuration file that powers Prezet's markdown blogging capabilities.
date: 2024-06-27
category: Getting Started
image: /prezet/img/ogimages/configuration.webp
---

The configuration file is published as part of the package's installation command, but you can manually publish or re-publish it at any time by running:

```bash
php artisan vendor:publish --provider="BenBjurstrom\Prezet\PrezetServiceProvider" --tag=prezet-config
```

After installation or re-publication, the `config/prezet.php` file will look like this:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Filesystem Configuration
    |--------------------------------------------------------------------------
    |
    | This setting determines the filesystem disk used by Prezet to store and
    | retrieve markdown files and images. By default, it uses the 'prezet' disk.
    |
    */

    'filesystem' => [
        'disk' => env('PREZET_FILESYSTEM_DISK', 'prezet'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Slug Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how document slugs are generated. The source can be 'filepath'
    | or 'title'. Note that a slug defined in front matter will take precedence
    | over the generated slug. When 'keyed' is true, the key present in the
    | front matter key will be appended to the slug (e.g., my-post-123).
    |
    */

    'slug' => [
        'source' => 'filepath', // 'filepath' or 'title'
        'keyed' => false, // 'true' or 'false'
    ],

    /*
    |--------------------------------------------------------------------------
    | CommonMark
    |--------------------------------------------------------------------------
    |
    | Configure the CommonMark Markdown parser. You can specify the extensions
    | to be used and their configuration. Extensions are added in the order
    | they are listed.
    |
    */

    'commonmark' => [

        'extensions' => [
            League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension::class,
            League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension::class,
            League\CommonMark\Extension\ExternalLink\ExternalLinkExtension::class,
            League\CommonMark\Extension\FrontMatter\FrontMatterExtension::class,
            BenBjurstrom\Prezet\Extensions\MarkdownBladeExtension::class,
            BenBjurstrom\Prezet\Extensions\MarkdownImageExtension::class,
        ],

        'config' => [
            'heading_permalink' => [
                'html_class' => 'prezet-heading',
                'id_prefix' => 'content',
                'apply_id_to_heading' => false,
                'heading_class' => '',
                'fragment_prefix' => 'content',
                'insert' => 'before',
                'min_heading_level' => 2,
                'max_heading_level' => 3,
                'title' => 'Permalink',
                'symbol' => '#',
                'aria_hidden' => false,
            ],
            'external_link' => [
                'internal_hosts' => 'www.example.com', // Don't forget to set this!
                'open_in_new_window' => true,
                'html_class' => 'external-link',
                'nofollow' => 'external',
                'noopener' => 'external',
                'noreferrer' => 'external',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Images
    |--------------------------------------------------------------------------
    |
    | Configure how image tags are handled when converting from markdown.
    | 'widths' defines the various widths for responsive images,
    | while 'sizes' indicates the sizes attribute.
    | Set 'zoomable' to true to enable Alpine-driven zoom on click.
    */

    'image' => [

        'widths' => [
            480, 640, 768, 960, 1536,
        ],

        'sizes' => '92vw, (max-width: 1024px) 92vw, 768px',

        'zoomable' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap
    |--------------------------------------------------------------------------
    | The sitemap origin is used to generate absolute URLs in your sitemap.
    | An origin consists of a scheme/host/port combination, but no path.
    | (e.g., https://example.com:8000)
    |
    */

    'sitemap' => [
        'origin' => env('PREZET_SITEMAP_ORIGIN', env('APP_URL')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Metadata
    |--------------------------------------------------------------------------
    |
    | Prezet uses these values for JSON-LD structured data. 'authors' defines
    | named authors you can reference in front matter, and 'publisher' is used
    | as the default publisher for all content.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Structured Data
    |--------------------------------------------------------------------------
    |
    | Prezet uses these values for JSON-LD structured data. 'authors' defines
    | named authors you can reference in front matter, and 'publisher' is used
    | as the default publisher for all content.
    |
    */

    // https://schema.org/author
    'authors' => [
        'prezet' => [
            '@type' => 'Person',
            'name' => 'Prezet Author',
            'url' => 'https://prezet.com',
            'image' => 'https://prezet.com/favicon.svg',
        ],
    ],

    // https://schema.org/publisher
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Prezet',
        'url' => 'https://prezet.com',
        'logo' => 'https://prezet.com/favicon.svg',
        'image' => 'https://prezet.com/ogimage.png',
    ],
];
```

## Filesystem Configuration

By default, Prezet uses the `'prezet'` disk for reading and storing markdown files. You can change this by updating the `PREZET_FILESYSTEM_DISK` environment variable or directly editing the `filesystem` array above.

## Slug Configuration

The `'slug'` array controls how document URLs are generated:

- **`source`**: Determines how the base slug is generated
  - `'filepath'`: Uses the markdown file's path (default)
  - `'title'`: Uses the document's title from front matter
- **`keyed`**: When `true`, appends the front matter `key` to the slug (e.g., `my-post-123`)

Note that a `slug` defined in front matter will always take precedence over these generated slugs.

## CommonMark Configuration

Prezet uses [league/commonmark](https://commonmark.thephpleague.com/) for markdown parsing. In the `'commonmark'` array, you can:

- Define the **extensions** array to add or remove functionality (e.g., `HeadingPermalinkExtension`, `ExternalLinkExtension`, etc.).
- Provide a `'config'` array with per-extension options, like the `heading_permalink` or `external_link` keys shown above.

### HeadingPermalinkExtension Options

For example, if you enable the `HeadingPermalinkExtension`:

```php
'heading_permalink' => [
    'html_class' => 'mr-2 scroll-mt-12',
    'id_prefix' => 'content',
    'apply_id_to_heading' => false,
    'heading_class' => '',
    'fragment_prefix' => 'content',
    'insert' => 'before',
    'min_heading_level' => 2,
    'max_heading_level' => 3,
    'title' => 'Permalink',
    'symbol' => '#',
    'aria_hidden' => true,
],
```

the extension will automatically insert permalink anchors for headings in your rendered HTML.

## Image Optimization

The `'image'` array controls how inline markdown images are transformed:

- **`widths`**: Defines the widths used in `srcset`.
- **`sizes`**: Specifies the sizes attribute for responsive images.
- **`zoomable`**: Enables an Alpine-based zoom feature on click.

See [Optimized Images](features/images) for details on how Prezet automatically generates multiple responsive image URLs and updates your markdown output to reference them.

## Metadata (JSON-LD)

Prezet automatically includes [JSON-LD](https://developers.google.com/search/docs/appearance/structured-data/intro-structured-data) metadata for each document, enhancing its visibility in search engines and social media. This is driven by two arrays:

- **`authors`**: An associative array mapping from an `author` key in your front matter to a fully-defined schema.org object.
- **`publisher`**: The fallback publisher metadata used when the document has no specific publisher or image.

For more on how this is injected into your templates, see the [JSON-LD documentation](/features/jsonld).

## Additional Customizations

Prezet offers various customization options beyond the configuration file. Here are some additional resources for customizing different aspects of Prezet:

- [Customizing Routes](customize/routes)
- [Customizing Blade Views](customize/blade-views)
- [Customizing Controllers](customize/controllers)
- [Customizing Front Matter](customize/frontmatter)

These articles will guide you through tailoring Prezet to your specific needs.
