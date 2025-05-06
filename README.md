<div align="center">
    <img src="https://prezet.com/ogimage.png" width="600" alt="PREZET">
</div>

<p align="center">
<a href="https://packagist.org/packages/prezet/prezet"><img src="https://img.shields.io/packagist/v/prezet/prezet.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://github.com/prezet/prezet/actions?query=workflow%3Arun-tests+branch%3Amain"><img src="https://img.shields.io/github/actions/workflow/status/prezet/prezet/run-tests.yml?branch=main&label=tests&style=flat-square" alt="GitHub Tests Action Status"></a>
<a href="https://github.com/prezet/prezet/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain"><img src="https://img.shields.io/github/actions/workflow/status/prezet/prezet/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square" alt="GitHub Code Style Action Status"></a>
</p>

# Prezet: Markdown Blogging for Laravel

**Go from markdown files to SEO-friendly blogs, articles, and documentation in seconds!** The Prezet framework makes it easy to parse, index, and serve your Markdown content efficiently within a Laravel application.

*Looking for a ready-to-use frontend? Check out our offical template packages:*
- [prezet/docs-template](https://github.com/prezet/docs-template)
- [prezet/blog-template](https://github.com/prezet/blog-template)

<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/prezet/prezet/main/art/screenshot-dark.png">
  <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/prezet/prezet/main/art/screenshot-light.png">
  <img alt="Screenshot of Prezet blog" src="https://raw.githubusercontent.com/prezet/prezet/main/art/screenshot-light.png">
</picture>

## Table of Contents

*   [Framework Features](#-framework-features)
*   [Quick Start](#-quick-start)
*   [Documentation](#documentation)

## ✨ Framework Features

<dl>
  <dt>•&nbsp;SQLite Index</dt>
  <dd>Indexes your markdown files to support search, pagination, sorting, and filtering.</dd>

  <dt>•&nbsp;Automatic Image Optimization</dt>
  <dd>Handles image processing, including compression, scaling, and generating responsive `srcset` attributes.</dd>

  <dt>•&nbsp;Validated Front Matter</dt>
  <dd>Define expected front matter fields and automatically cast them into validated Data Transfer Objects (DTOs) for type-safe access in your application.</dd>

  <dt>•&nbsp;Open Graph (OG) images</dt>
  <dd>Generate OG images from front matter using a customizable template.</dd>

  <dt>•&nbsp;Dynamic Table of Contents</dt>
  <dd>Automatically extracts headings from your Markdown content to generate data for a nested Table of Contents.</dd>

  <dt>•&nbsp;SEO Optimization</dt>
  <dd>Automatically generate meta tags based on front matter data for better search engine discoverability.</dd>

  <dt>•&nbsp;Blade Components</dt>
  <dd>Include Laravel Blade components in your markdown for enriched, interactive content.</dd>
</dl>

## 🚀 Quick Start

1.  **Install the Prezet framework:**
    ```bash
    # Install the framework package
    composer require prezet/prezet

    # Run the framework installer
    php artisan prezet:install
    ```

2.  **Install a Frontend Template:**

    The Prezet framework provides the backend engine. A template package provides the frontend (routes, controllers, views, CSS).

    *Example using the Official Docs Template:*
    ```bash
    # Install the template package
    composer require prezet/docs-template --dev

    # Run the template's installer
    php artisan docs-template:install
    ```

3.  **Index Your Content:**
    After adding or modifying Markdown files in your content directory update the Prezet index:
    ```bash
    php artisan prezet:index --fresh
    ```
    This command scans your content directory and updates the SQLite index with the latest front matter information. Run this whenever you:
    *   Add new Markdown files.
    *   Change a file's slug (filename).
    *   Modify front matter and need it reflected in listings or searches.
        *(Changes to the main body content of Markdown files are reflected automatically when viewing a single page).*

4.  **Start Your Server:**
    ```bash
    php artisan serve
    ```
    Visit the routes defined by your installed frontend template (e.g., `/prezet` if using `prezet/docs-template`).

## Documentation

Detailed documentation is available at [prezet.com](https://prezet.com)

## Credits

*   [Ben Bjurstrom](https://github.com/benbjurstrom)
*   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
