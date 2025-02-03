---
title: Typed Front Matter
date: 2024-05-06
category: Features
excerpt: Prezet uses typed front matter for robust content management and validation.
image: /prezet/img/ogimages/features-frontmatter.webp
---

Front matter is the YAML metadata at the top of a markdown file, surrounded by triple-dash lines (`---`). Prezet parses and validates this metadata into a strongly typed object to ensure consistent, reliable content management.

Below is a quick example of front matter for a post:

```yaml
---
title: Typed Front Matter
date: 2024-05-06
category: Features
excerpt: "Prezet uses typed front matter for robust content management."
image: "/prezet/img/ogimages/example.png"
draft: false
author: 'jane_doe'
tags: [markdown, content]
---
```

## How Prezet Uses Front Matter

1. **Content Display**: Your blog's title, excerpt, and related metadata are rendered in the views.
2. **SEO Optimization**: The front matter drives SEO tags, including page title, description, and Open Graph image.
3. **Content Organization**: Fields like `category`, `tags`, and `draft` help categorize, filter, or hide content.

## The FrontmatterData Class

Prezet validates front matter using the `FrontmatterData` class, which employs the [laravel-validated-dto](https://wendell-adriel.gitbook.io/laravel-validated-dto) package. Key fields include:

```php
class FrontmatterData extends ValidatedDTO
{
    #[Rules(['required', 'string'])]
    public string $title;

    #[Rules(['required', 'string'])]
    public string $excerpt;

    #[Rules(['nullable', 'string'])]
    public ?string $category;

    #[Rules(['nullable', 'string'])]
    public ?string $image;

    #[Rules(['bool'])]
    public bool $draft;

    #[Rules(['required'])]
    public Carbon $date;

    #[Rules(['nullable', 'string'])]
    public ?string $author;

    #[Rules(['nullable', 'string'])]
    public ?string $slug;

    #[Rules(['nullable', 'string'])]
    public ?string $key;

    #[Rules(['array'])]
    public array $tags; // e.g. ['markdown', 'content']
}
```

If your front matter is missing any required fields (such as `title`) or fails validation (e.g., providing a string where a date is expected), Prezet throws an error to help you quickly spot and fix issues.

### Bulk Validation With The Index Command

Whenever you run:

```bash
php artisan prezet:index
```

Prezet scans all markdown files in your content folder and validates their front matter against the `FrontmatterData` rules. If a file fails validation, you'll see a clear error message detailing what went wrong.

## DocumentData vs FrontmatterData

Prezet actually makes use of two data classes:

**1. FrontmatterData**  
  Focuses solely on validating the YAML metadata in your markdown (e.g., `title`, `excerpt`, `tags`, etc.). It ensures your content stays consistent and helps power features like SEO tags and category filtering.

**2. DocumentData**  
  Reflects the broader document record that Prezet stores in its [SQLite index](/index). It includes front matter **plus** additional properties like `slug`, `hash`, and timestamps for when the document was created or updated.

In short:

- **FrontmatterData** = typed representation of your file's YAML front matter.
- **DocumentData** = typed representation of the entire document record, combining front matter with system-level details for indexing, versioning, and retrieval.

By splitting these responsibilities, Prezet provides both strong validation for your front matter **and** seamless integration with Laravel's database features. It keeps your markdown's metadata flexible while maintaining fast lookups and advanced query capabilities in the SQLite index.

### Property Duplication

You may notice some properties appear in both `FrontmatterData` and as top-level properties in `DocumentData` (like `category`, `draft`, `slug`, and `key`). This duplication is intentional:

-   `FrontmatterData` maintains a complete and accurate representation of your markdown front matter
-   `DocumentData` represents the database record, where certain properties are elevated to columns for efficient filtering and searching
-   Properties that appear in both places serve different purposes: the top-level properties in `DocumentData` are optimized for database operations, while the nested `frontmatter` property preserves the original metadata structure.

## Customizing Front Matter
While Prezet provides a default structure for front matter, you can customize it to fit your specific needs. For detailed instructions on how to extend or modify the `FrontmatterData` class, please refer to the documentation on [Customizing front matter](/customize/frontmatter).

