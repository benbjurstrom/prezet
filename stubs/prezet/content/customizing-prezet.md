---
title: "Customizing Prezet"
date: 2024-07-01
category: "Configuration"
excerpt: "Learn how to customize Prezet to fit your needs."
---

Prezet is highly customizable. This post will give you an overview of some of the customization options and refer you to the relevant documentation located at [prezet.com](https://prezet.com).

## Configuration File

The `config/prezet.php` file allows you to customize various aspects of Prezet such as the applied commonmark extensions and responsive image sizes:

```php
return [
    'commonmark' => [
        'extensions' => [
            // Your custom extensions here
        ],
    ],
    'image' => [
        'widths' => [480, 640, 768, 960, 1536],
        'sizes' => '92vw, (max-width: 1024px) 92vw, 768px',
    ],
    // More configuration options...
];
```

For more details on configuration, see the [Configuration](https://prezet.com/configuration) guide.

## Custom Routes

You can easily customize the routes used by Prezet. See the [Routes](https://prezet.com/customize/routes) customization guide for more details.

## Custom Frontmatter

Prezet allows you to define custom frontmatter structures. See the [Front Matter](https://prezet.com/customize/frontmatter) guide for more information.

## Custom Blade Views

You can publish and customize the Blade views used by Prezet:

```bash
php artisan vendor:publish --provider="BenBjurstrom\Prezet\PrezetServiceProvider" --tag=prezet-views
```

This will allow you to modify the layout and styling of your blog. Learn more about [Blade Views](https://prezet.com/customize/blade-views) customization.

## Custom Controllers

For advanced customization, you can modify Prezet's controllers. Check out the [Controllers](https://prezet.com/customize/controllers) guide for details.

Remember, Prezet is designed to be flexible and extensible. Don't hesitate to dive in and make it your own!
