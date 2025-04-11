<div align="center">
    <img src="https://prezet.com/ogimage.png" width="600" alt="PREZET">
</div>

<p align="center">
<a href="https://packagist.org/packages/benbjurstrom/prezet"><img src="https://img.shields.io/packagist/v/benbjurstrom/prezet.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://github.com/benbjurstrom/prezet/actions?query=workflow%3Arun-tests+branch%3Amain"><img src="https://img.shields.io/github/actions/workflow/status/benbjurstrom/prezet/run-tests.yml?branch=main&label=tests&style=flat-square" alt="GitHub Tests Action Status"></a>
<a href="https://github.com/benbjurstrom/prezet/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain"><img src="https://img.shields.io/github/actions/workflow/status/benbjurstrom/prezet/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square" alt="GitHub Code Style Action Status"></a>
</p>

# Prezet: Markdown Blogging for Laravel

**Go from markdown files to SEO-friendly blogs, articles, and documentation in seconds!** Prezet provides the backend power to parse, index, and serve your Markdown content efficiently within a Laravel application.

*(Looking for a ready-to-use frontend? Check out template packages like [prezet/docs-template](https://github.com/prezet/docs-template)!)*

<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/benbjurstrom/prezet/main/art/screenshot-dark.png">
  <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/benbjurstrom/prezet/main/art/screenshot-light.png">
  <img alt="Screenshot of Prezet blog" src="https://raw.githubusercontent.com/benbjurstrom/prezet/main/art/screenshot-light.png">
</picture>

## Table of Contents

*   [Core Features](#-core-features)
*   [Quick Start](#-quick-start)
*   [Documentation](#-documentation)

## ✨ Core Features

✅ **Installs in seconds**<br>Set up your project with a single command.

✅ **Automatic Image Optimization**<br>Automated image compression, scaling, and responsive srcset handling.

✅ **SQLite Index**<br>Indexes your markdown files to support search, pagination, sorting, and filtering.

✅ **Validated Front Matter**<br>Cast front matter into validated DTOs for consistency across your content.

✅ **Open Graph (OG) images**<br>Generate OG images from front matter using a customizable template.

✅ **Dynamic Table of Contents**<br>Automatically generate a nested, scroll-synced TOC from your article's headings.

✅ **SEO Optimization**<br>Automatically generate meta tags based on front matter data for better search engine discoverability.

✅ **Blade Components**<br>Include Laravel Blade components in your markdown for enriched, interactive content.

✅ **Complete Customization**<br>Prezet is built to allow full customization of your routes, front matter, and blade templates.

## 🚀 Quick Start

1.  **Install Prezet Core:**
    ```bash
    composer require benbjurstrom/prezet:^1.0
    ```
    *(Replace `^1.0` with the desired version constraint).*

2.  **Install a Frontend Template (Required):**
    Prezet Core provides the backend engine. You need a template package to provide the frontend (routes, controllers, views, CSS).

    *Example using the Official Docs Template:*
    ```bash
    # Install the template package
    composer require prezet/docs-template --dev

    # Run the template's installer
    php artisan docs-template:install
    ```
    *(This example installs the necessary frontend files and then removes the `prezet/docs-template` package).*

3.  **Index Your Content:**
    After adding or modifying Markdown files in your content directory (e.g., `base_path('prezet')` if using the docs template), update the Prezet index:
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
