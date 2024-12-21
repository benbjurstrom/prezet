<div align="center">
    <img src="https://prezet.com/ogimage.png" width="600" alt="PREZET">
</div>

<p align="center">
<a href="https://packagist.org/packages/benbjurstrom/prezet"><img src="https://img.shields.io/packagist/v/benbjurstrom/prezet.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://github.com/benbjurstrom/prezet/actions?query=workflow%3Arun-tests+branch%3Amain"><img src="https://img.shields.io/github/actions/workflow/status/benbjurstrom/prezet/run-tests.yml?branch=main&label=tests&style=flat-square" alt="GitHub Tests Action Status"></a>
<a href="https://github.com/benbjurstrom/prezet/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain"><img src="https://img.shields.io/github/actions/workflow/status/benbjurstrom/prezet/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square" alt="GitHub Code Style Action Status"></a>
</p>


# Prezet: Markdown Blogging for Laravel

Go from markdown files to SEO-friendly blogs, articles, and documentation in seconds!

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

✅ **Installs in seconds**<br>Set up your project with a single command.

✅ **Automatic Image Optimization**<br>Automated image compression, scaling, and responsive srcset handling.

✅ **SQLite Index**<br>Indexes your markdown files to support search, pagination, sorting, and filtering.

✅ **Validated Front Matter**<br>Cast front matter into validated DTOs for consistency across your content.

✅ **Open Graph (OG) images**<br>Generate OG images from front matter using a customizable template.

✅ **Dynamic Table of Contents**<br>Automatically generate a nested, scroll-synced TOC from your article's headings.

✅ **SEO Optimization**<br>Automatically generate meta tags based on front matter data for better search engine discoverability.

✅ **Blade Components**<br>Include Laravel Blade components in your markdown for enriched, interactive content.

✅ **Complete Customization**<br>Prezet is built to allow full customization of your routes, front matter, and blade templates.

## 🚀 Quick start

#### Install the Prezet package:

```bash
composer require benbjurstrom/prezet
```

#### Run the package installer:

_⚠️ NOTE: Before running the installer on an existing application it is recommended to switch to a clean branch so you can see the changes Prezet made during the installation process._

```bash
php artisan prezet:install
```

#### Update the index:

After adding or modifying your markdown files, you need to update the index to reflect these changes:

```bash
php artisan prezet:index
```

This command updates the SQLite index with the latest front matter information from your markdown files. Run this command whenever you:

- Add a new markdown file 
- Change a markdown file's slug 
- Modify front matter and want to see those changes reflected on the index page

Note that changes to the main content of your markdown files don't require updating the index, as this content is read directly from the file when displaying a single post.

#### Start your server:
```bash
php artisan serve
```

Check out your new markdown blog at [http://localhost:8000/prezet](http://localhost:8000/prezet)

## Documentation
Detailed documentation available at [prezet.com](https://prezet.com)

## Credits

- [Ben Bjurstrom](https://github.com/benbjurstrom)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
