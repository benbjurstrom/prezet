---
title: Automating OG Images with Prezet
date: 2024-05-07
category: Features
excerpt: This article covers how to automatically generate Open Graph images for your blog posts using Prezet. Learn how to customize the OG image template and troubleshoot common issues.
image: /prezet/img/ogimages/features-ogimage.webp
---

Open Graph (OG) images are visual previews of your web pages that appear when content is shared on social media platforms. These images can significantly boost engagement and click-through rates.

Here's how this page appears when posted on Threads:

```html +parse
<x-prezet::threads
    id="C-yDq7mS5II"
    username="benbjurstrom"
/>
```

## Adding OG Images Manually

You can easily specify an OG image for any blog post by adding the `image` key to your frontmatter:

```yaml
---
title: My Amazing Blog Post
date: 2024-05-07
category: Blog
excerpt: 'This is an excerpt of my amazing blog post.'
image: '/prezet/img/ogimages/custom-ogimage.png'
---
```

The `image` field should contain the URL path to your image. For more details on using frontmatter in Prezet refer to the [frontmatter documentation](/features/frontmatter).

## Automatic OG Image Generation

```html +parse
<x-prezet::alert
    type="info"
    title="Browsershot Requirement"
    body="OG image generation requires Browsershot to be properly set up in your Laravel environment."
/>
```

While manually specifying ogimages gives you full control, Prezet's automatic OG image generation saves time and ensures consistency across your blog. Here's how it works:

### The OgimageCommand

This Artisan command generates OG images for your blog posts. You can use it in two ways:

1. For a specific markdown file:
   ```
   php artisan prezet:ogimage
   ```
   This will prompts you to enter the name of the markdown file.

2. For all markdown files:
   ```
   php artisan prezet:ogimage --all
   ```
   This generates OG images for all markdown files in your `content` directory.

Regardless of the method you choose, the command automatically updates the frontmatter of your markdown files with the URL of the generated OG image.

```html +parse
<x-prezet::alert
    type="warning"
    title="Troubleshooting Tip"
    body="If you're generating OG Images in a local environment, make sure to set the `APP_URL` in your `.env` file to your local development URL."
/>
```

## Customizing the OG Image Template

Prezet allows you to customize the appearance of your OG images by simply editing a package provided Blade template.

### Preview the Template
You can preview the template by navigating to the ogimage route in your browser. The route follows this pattern:

   ```
   /prezet/ogimage/{slug}
   ```

   Replace `{slug}` with the slug of your markdown file. For example here's a link to the ogimage template for this post: ['/prezet/ogimage/features/ogimage'](/prezet/ogimage/features/ogimage).

### Modify the Template
The ogimage template was added to your application as part of the installation process. The blade file is located in `resources/views/vendor/prezet/ogimage.blade.php`.

   You can modify this template to change colors, fonts, layout, or add additional elements like logos or images.

4. **Adding More Data**: If you want to include more data from your markdown file in the ogimage, you can modify the `OgimageController` to pass additional variables to the view, and then use these variables in your `ogimage.blade.php` template.

Remember, the ogimage should be visually appealing and representative of your content while being readable at small sizes.

For more information about customizing Prezet's views, including the ogimage template, please refer to the documentation at `/customize/blade-views`.
