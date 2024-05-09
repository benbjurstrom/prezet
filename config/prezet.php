<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Blog Path
    |--------------------------------------------------------------------------
    |
    | This is the path to the blog. It is used to generate the routes for the
    | blog posts. The path should not have a leading or trailing slash.
    |
    |
    */

    'path' => 'prezet',

    /*
    |--------------------------------------------------------------------------
    | Data classes
    |--------------------------------------------------------------------------
    |
    | These classes are used to store the markdown information. You can
    | create your own data classes and use them here.
    |
    |
    */

    'data' => [
        'frontmatter' => BenBjurstrom\Prezet\Data\FrontmatterData::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | CommonMark
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the CommonMark markdown parser. You can
    | configure the extensions that are used and the configuration for each
    | extension. The extensions are added in the order they are listed.
    |
    |
    */

    'commonmark' => [

        'extensions' => [
            League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension::class,
            League\CommonMark\Extension\FrontMatter\FrontMatterExtension::class,
            League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension::class,
            BenBjurstrom\Prezet\Extensions\MarkdownBladeExtension::class,
            BenBjurstrom\Prezet\Extensions\MarkdownImageExtension::class,
        ],

        'config' => [
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
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Images
    |--------------------------------------------------------------------------
    | These configuration options determine how image tags are handled when
    | converting from markdown.
    |
    | Sizes is used to determine the size of the image, or how much of the
    | viewport the image takes up at each viewport width.
    */

    'image' => [

        'path' => '/prezet/img/',

        'widths' => [
            480, 640, 768, 960, 1536,
        ],

        'sizes' => '92vw, (max-width: 1024px) 92vw, 768px',
    ],
];
