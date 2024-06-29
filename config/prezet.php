<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data Classes
    |--------------------------------------------------------------------------
    |
    | These classes are used to store markdown information in a Validated DTO.
    | You can override the default classes with your own and configure Pezet to
    | use them here.
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
    | Configure the CommonMark Markdown parser. You can specify the extensions
    | to be used and their configuration. Extensions are added in the order
    | they are listed.
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
    |
    | Configure how image tags are handled when converting from markdown.
    |
    | 'path' specifies the route for serving images.
    | 'widths' defines the various widths for responsive images.
    | 'sizes' indicates the sizes attribute for responsive images.
    */

    'image' => [

        'widths' => [
            480, 640, 768, 960, 1536,
        ],

        'sizes' => '92vw, (max-width: 1024px) 92vw, 768px',
    ],
];
