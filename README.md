<p align="center">
    <img src="https://raw.githubusercontent.com/benbjurstrom/prezet/1-proof-of-concept/art/logo.png" width="256" alt="PREZET">
</p>

# Prezet: A Supercharged Markdown Blogging Preset for Laravel

Use this package to transforms your markdown files into SEO-friendly blogs, articles, and documentation. It offers automatic image optimization, dynamic tables of contents, validated frontmatter DTOs, and integrated Blade components.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/benbjurstrom/prezet.svg?style=flat-square)](https://packagist.org/packages/benbjurstrom/prezet)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/benbjurstrom/prezet/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/benbjurstrom/prezet/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/benbjurstrom/prezet/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/benbjurstrom/prezet/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/benbjurstrom/prezet.svg?style=flat-square)](https://packagist.org/packages/benbjurstrom/prezet)

## Table of contents
- [Features](https://github.com/benbjurstrom/prezet#features)
- [Quick Start](https://github.com/benbjurstrom/prezet#quick-start)
- [Configuration](https://github.com/benbjurstrom/prezet#configuration)
- [Documentation](https://github.com/benbjurstrom/prezet#documentation)

## 🌟 Features

### Automatic Image Optimization:
Streamline your media with automated compression, WebP conversion, and responsive srcset handling.

### Dynamic Table of Contents:
Extracts H2 and H3 headings to generate a nested, scroll-synced TOC.

### Validated Frontmatter:
Confidently validate and cast frontmatter data into structured DTOs for consistency across your content.

### SUMMARY.md Support:
Easily organize your blog-level TOC similar to GitBook.

### Configurable Markdown:
Tailor CommonMark extensions, route settings, and frontmatter DTOs through the configuration file. 

### SEO Optimization:
Automatically generate SEO tags based on the frontmatter data for better discoverability.

### Blade Components in Markdown:
Effortlessly include Laravel Blade components in your markdown for enriched, interactive content.

### Complete Customization:
Rapidly configure routes, controllers, views, and more, with modular action classes for flexible customization.

## 🚀 Quick start

## Installation

Install with composer:

```bash
composer create-project laravel/laravel prezet
cd prezet
composer require laravel/breeze --dev
git init && git add . && git commit -m "Initial commit"
```

Install with composer:

```bash
composer require benbjurstrom/prezet
```

Add the prezet storage disk in `config/filestem.php`:

```php
'prezet' => [
    'driver' => 'local',
    'root' => storage_path('content'),
    'throw' => false,
],
```

Run the installer:

```bash
php artisan prezet:install
```

Publish the config file with:

```bash
php artisan vendor:publish --tag="prezet-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="prezet-views"
```

## Documentation
Access detailed documentation at [prezet.com](https://prezet.com)

## Credits

- [Ben Bjurstrom](https://github.com/benbjurstrom)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
