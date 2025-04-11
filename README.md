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

✳️ **SQLite Index**<br><sub>Indexes your markdown files to support search, pagination, sorting, and filtering.</sub>

✳️ **Automatic Image Optimization**<br><sub>Handles image processing, including compression, scaling, and generating responsive `srcset` attributes.</sub>

✳️ **Validated Front Matter**<br><sub>Define expected front matter fields and automatically cast them into validated Data Transfer Objects (DTOs) for type-safe access in your application.</sub>

✳️ **Open Graph (OG) images**<br><sub>Generate OG images from front matter using a customizable template.</sub>

✳️ **Dynamic Table of Contents**<br><sub>Automatically extracts headings from your Markdown content to generate data for a nested Table of Contents.</sub>

✳️ **SEO Optimization**<br><sub>Automatically generate meta tags based on front matter data for better search engine discoverability.</sub>

✳️ **Blade Components**<br><sub>Include Laravel Blade components in your markdown for enriched, interactive content.</sub>

## 🚀 Quick Start

1.  **Install Prezet Core:**
    ```bash
    composer require prezet/prezet:^1.0
    ```

    # Run the core installer
    ```bash
    php artisan prezet:install
    ```

2.  **Install a Frontend Template:**
    Prezet Core provides the backend engine. A template package provides the frontend (routes, controllers, views, CSS).

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
