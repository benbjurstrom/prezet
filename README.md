# Prezet: Markdown Blogging Engine for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/benbjurstrom/prezet.svg?style=flat-square)](https://packagist.org/packages/benbjurstrom/prezet)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/benbjurstrom/prezet/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/benbjurstrom/prezet/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/benbjurstrom/prezet/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/benbjurstrom/prezet/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/benbjurstrom/prezet.svg?style=flat-square)](https://packagist.org/packages/benbjurstrom/prezet)

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

Prezet's core engine focuses on efficiently processing and managing your Markdown content:

✅ **SQLite Index**
Indexes your markdown files into a flat-file SQLite database for fast searching, pagination, sorting, and filtering, without requiring a traditional database.

✅ **Automatic Image Optimization**
Handles image processing, including compression, scaling, and generating responsive `srcset` attributes.

✅ **Validated Front Matter**
Define expected front matter fields and automatically cast them into validated Data Transfer Objects (DTOs) for type-safe access in your application.

✅ **Open Graph (OG) Image Generation**
Provides the backend logic to generate Open Graph images dynamically based on front matter data. *(Frontend template required for display).*

✅ **Dynamic Table of Contents (TOC) Generation**
Automatically extracts headings from your Markdown content to generate data for a nested Table of Contents. *(Frontend template required for display).*

✅ **SEO Optimization Logic**
Generates structured data for meta tags (title, description, OG tags) based on front matter. *(Frontend template required for display).*

✅ **Blade Component Support**
Easily embed your existing Laravel Blade components directly within your Markdown files.

✅ **Configurable & Customizable**
Offers configuration options for content paths, DTOs, image handling, and more.

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
