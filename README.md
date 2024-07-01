<p align="center">
    <img src="https://raw.githubusercontent.com/benbjurstrom/prezet/main/art/logo.png" width="256" alt="PREZET">
</p>

# Prezet: Markdown Blogging for Laravel

Transform your markdown files into SEO-friendly blogs, articles, and documentation with Prezet. Including built in automatic image optimization, dynamic tables of contents, validated front matter DTOs, and integrated Blade components.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/benbjurstrom/prezet.svg?style=flat-square)](https://packagist.org/packages/benbjurstrom/prezet)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/benbjurstrom/prezet/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/benbjurstrom/prezet/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/benbjurstrom/prezet/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/benbjurstrom/prezet/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)

## Table of contents
- [Features](https://github.com/benbjurstrom/prezet#user-content--features)
- [Quick Start](https://github.com/benbjurstrom/prezet#user-content--quick-start)
- [Documentation](https://github.com/benbjurstrom/prezet#user-content--documentation)

## 🌟 Features

- **Installs in seconds:**  Set up your project with a single command.
- **Automatic Image Optimization:** Streamline your media with automated compression, WebP conversion, and responsive srcset handling.
- **Dynamic Table of Contents:** Automatically generate a nested, scroll-synced TOC from your article's H2 and H3 headings.
- **Validated Frontmatter:** Validate and cast frontmatter data into structured DTOs for consistency across your content.
- **SUMMARY.md Support:** Organize your blog level hierarchy with simple headings and links.
- **Configurable Markdown:** Tailor CommonMark extensions, route settings, and front matter DTOs through the package's configuration file.
- **SEO Optimization:** Automatically generate meta tags based on front matter data for better search engine discoverability.
- **Blade Components:** Include Laravel Blade components in your markdown for enriched, interactive content.
- **Complete Customization:** Prezet is built on top of modular action classes allowing you full customization of the package's built in controllers.

## 🚀 Quick start

#### Create a fresh Laravel install

```bash
composer create-project laravel/laravel prezet
cd prezet
git init && git add . && git commit -m "Initial commit"
```

#### Install the Prezet package:

```bash
composer require benbjurstrom/prezet
```

#### Run the package installer:

_⚠️ NOTE: Before running the installer on existing applications it is recommended to switch to a clean branch. As part of the install process Prezet configures your application with Tailwind CSS. Any existing Tailwind configuration will be overwritten._

```bash
php artisan prezet:install
```

#### Start your server:
```bash
php artisan serve
```

Check out your new markdown blog at [http://localhost:8000/prezet](http://localhost:8000/prezet)

## Documentation
Access detailed documentation at [prezet.com](https://prezet.com)

## Credits

- [Ben Bjurstrom](https://github.com/benbjurstrom)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
