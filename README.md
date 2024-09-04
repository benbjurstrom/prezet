<div align="center">
    <img src="https://prezet.com/ogimage.png" width="600" alt="PREZET">
</div>

# Prezet: Markdown Blogging for Laravel

Transform your markdown files into SEO-friendly blogs, articles, and documentation with Prezet. Including built in automatic image optimization, dynamic tables of contents, validated front matter DTOs, and integrated Blade components.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/benbjurstrom/prezet.svg?style=flat-square)](https://packagist.org/packages/benbjurstrom/prezet)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/benbjurstrom/prezet/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/benbjurstrom/prezet/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/benbjurstrom/prezet/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/benbjurstrom/prezet/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![PHPStan Level 9](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg?style=flat)](https://github.com/benbjurstrom/prezet/blob/main/phpstan.neon.dist)

https://github.com/benbjurstrom/prezet/assets/12499093/771efccc-6ac3-414a-a852-62ce66e87f57

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

_⚠️ NOTE: Before running the installer on an existing application it is recommended to switch to a clean branch. As part of the install process Prezet configures your application with Tailwind CSS. Any existing Tailwind configuration will be overwritten._

```bash
php artisan prezet:install
```

#### Update the index:

After adding or modifying your markdown files, you need to update the index to reflect these changes:

```bash
php artisan prezet:index
```

This command updates the SQLite index with the latest frontmatter information from your markdown files. Run this command whenever you:

- Add a new markdown file 
- Change a markdown file's slug 
- Modify frontmatter and want to see those changes reflected on the index page

Note that changes to the main content of your markdown files don't require updating the index, as this content is read directly from the file when displaying a single post.

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
