---
title: Automated Sitemap Generation
date: 2024-07-22
category: Features
excerpt: Automatically generate sitemaps for your Prezet-powered blog.
image: /prezet/img/ogimages/features-sitemap.webp
---

Prezet includes a sitemap generation feature, allowing you to easily create and maintain sitemaps for your markdown-based blog. This feature leverages a lightweight fork of the [Spatie sitemap package](https://github.com/benbjurstrom/laravel-sitemap-lite) to generate XML sitemaps, enhancing your site's SEO and making it easier for search engines to crawl and index your content.

## Automatic Generation

The sitemap is automatically generated whenever you run the index update command:

```bash
php artisan prezet:index
```

This command not only updates the content index but also creates or updates the `prezet_sitemap.xml` file in your Laravel project's `public` directory. This integration ensures that your sitemap always reflects the current state of your content.

## Important Notes

1. **APP_URL Setting**: The hostname in the generated sitemap is determined by the `APP_URL` value in your `.env` file. Make sure to set this to your production hostname before running the command for your live site.

2. **Sitemap Index**: Although the `prezet_sitemap.xml` file can be directly submitted to the Google search console, it can also be included in a main sitemap index file. Here's an example of how you might structure your main `sitemap.xml`:

   ```xml
   <?xml version="1.0" encoding="UTF-8"?>
   <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
     <sitemap>
       <loc>https://example.com/prezet_sitemap.xml</loc>
     </sitemap>
     <!-- Other sitemaps... -->
   </sitemapindex>
   ```

## Customization

The sitemap generation is handled by the `UpdateSitemapCommand` class, which is called as part of the index update process. If you need to customize the sitemap generation process (e.g., changing the change frequency or priority, or adding video sitemaps), you can modify this class.

For more advanced customization options, refer to the [Spatie sitemap package documentation](https://github.com/spatie/laravel-sitemap).

## Benefits

By implementing sitemaps, you're helping search engines understand the structure of your site and the frequency of your content updates. This can lead to:

- Improved crawling efficiency
- Faster discovery of new or updated content
- Better search engine rankings

Remember to submit your sitemap URL to search engine consoles (like Google Search Console) to ensure search engines are aware of your sitemap.
