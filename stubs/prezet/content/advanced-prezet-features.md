---
title: "Advanced Prezet Features"
date: 2024-07-02
category: "Tutorial"
excerpt: "Exploring some of the more advanced features of Prezet."
---

This post will delve into some of the more advanced features offered by Prezet. For full documentation, visit [prezet.com](https://prezet.com).

## Table of Contents

Prezet automatically generates a table of contents based on your headings. This feature is part of Prezet's markdown parsing capabilities.

## Code Highlighting

Prezet supports code highlighting out of the box:

```php
public function welcome()
{
    return view('welcome');
}
```

## Custom Frontmatter

You can add custom frontmatter to your posts:

```yaml
---
title: "Advanced Prezet Features"
date: 2024-07-02
category: "Tutorial"
excerpt: "Exploring some of the more advanced features of Prezet."
custom_field: "This is a custom field"
---
```

Learn more about [Typed Frontmatter](https://prezet.com/features/frontmatter) on prezet.com.

## SEO Optimization

Prezet automatically generates SEO tags based on your frontmatter. Read more about [SEO Tags](https://prezet.com/features/seo) in the documentation.

## OG Image Generation

Prezet can automatically generate Open Graph images for your posts. Check out the [OG Image Generation](https://prezet.com/features/ogimage) guide for details.
