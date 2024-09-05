---
title: Prezet Installation Guide
excerpt: Learn how to install Prezet and start creating SEO-friendly blogs, articles, and documentation. Follow these steps to set up your project and start blogging with Prezet.
slug: installation
date: 2024-08-28
category: Getting Started
image: /prezet/img/ogimages/installation.webp
---

This guide will walk you through the process of installing Prezet, a powerful markdown blogging package for Laravel. Follow these steps to set up your project and start creating SEO-friendly blogs, articles, and documentation.

## Step 1: Install the Prezet Package

You can install the Prezet package using Composer:

```bash
composer require benbjurstrom/prezet
```

## Step 2: Run the Package Installer

```html +parse
<x-prezet::alert
    type="warning"
    title="Existing Applications"
    body="The installation command will overwrite existing vite.config.js and postcss.config.js files. Be sure they're backed up before proceeding."
/>
```


To run the Prezet installer, execute the following Artisan command:


```bash
php artisan prezet:install
```

This command will:

- Copy the package configuration file to `config/prezet.php`
- Add the Prezet storage disk to your `config/filesystems.php`
- Copy content stubs to your project's storage directory
- Publish the vendor blade files
- Copy Tailwind configuration files
- Install necessary Node.js dependencies

## Step 3: Start Your Server

Once the installation is complete, you can start your Laravel development server:

```bash
php artisan serve
```

After starting your server, you can verify the installation by visiting:

[http://localhost:8000/prezet](http://localhost:8000/prezet)

You should now see your new markdown blog powered by Prezet!

## Step 4: Generate the SQLite Index
After installing Prezet and setting up your initial content, it's important to generate the SQLite index. Run the following Artisan command to create and populate the index:

```bash
php artisan prezet:index
```

For more information about the SQLite index and its purposes, refer to the [Prezet SQLite Index](/index) documentation.

## Next Steps

With Prezet installed, you're ready to start creating content and customizing your blog. Check out the other documentation pages to learn more about:

- [Writing markdown content](features/markdown)
- [Using Blade components in your markdown](features/blade)
- [Optimizing images](features/images)
- [Customizing routes](customize/routes), [front matter](customize/frontmatter), and more

Hope you enjoy blogging with Prezet!
