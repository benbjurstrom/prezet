---
title: Typed Front Matter
date: 2024-05-06
category: Features
excerpt: Prezet uses typed front matter for robust content management. Learn how to validate front matter, customize the `FrontmatterData` class, and ensure consistency across your blog.
image: /prezet/img/ogimages/features-frontmatter.webp
---

Front matter is the YAML metadata at the top of a markdown file enclosed by triple dashes (`---`). Prezet converts front matter into a strongly typed data transfer object (DTO) to ensure consistency and reliability.

As an example, here is the front matter for this very post:

```yaml
---
title: Typed Front Matter
date: 2024-05-06
category: Features
excerpt: 'Prezet uses typed front matter for robust content management.'
image: '/prezet/img/ogimages/example.png'
---
```

## How Prezet Uses Front Matter

Prezet utilizes front matter in several ways:

1. **Content Display**: Generates visible elements like titles, dates, and excerpts for your blog.
2. **SEO Optimization**: Renders SEO tags in the page's `<head>`, including title, description, and Open Graph attributes.
3. **Content Organization**: Uses categories, tags, and other metadata for structuring and organizing your content.

## Validating Front Matter

To ensure the integrity and consistency of your content, Prezet employs the `FrontmatterData` class to define and validate the structure of your front matter. This class uses the [laravel-validated-dto](https://wendell-adriel.gitbook.io/laravel-validated-dto) package for type safety and validation.

You can find the default `FrontmatterData` class here: [FrontmatterData.php](https://github.com/benbjurstrom/prezet/blob/main/src/Data/FrontmatterData.php)

The current `FrontmatterData` class includes the following fields:

```php
class FrontmatterData extends ValidatedDTO
{
    #[Rules(['required', 'string'])]
    public string $slug;

    #[Rules(['required', 'string'])]
    public string $title;

    #[Rules(['required', 'string'])]
    public string $excerpt;

    #[Rules(['nullable', 'string'])]
    public ?string $category;

    #[Rules(['array'])]
    public array $tags;

    #[Rules(['nullable', 'string'])]
    public ?string $image;

    #[Rules(['bool'])]
    public bool $draft;

    #[Rules(['required'])]
    public Carbon $createdAt;

    #[Rules(['required'])]
    public Carbon $updatedAt;

    // ... other methods ...
}
```

If the front matter is missing or does not pass validation, Prezet will throw an error and prevent the post from being rendered.

## Bulk Front Matter Validation

Prezet provides a convenient way to validate the front matter for all of your posts at once. Anytime you update the Prezet index SQLite file, Prezet also scans all your markdown files and checks that the front matter can be rendered into a valid DTO.

```bash
php artisan prezet:index
```

If any files contain invalid front matter, the command will output detailed error messages along with the file paths, allowing you to quickly identify and correct any issues.

## Understanding Front Matter Fields

- `slug`: A unique identifier for your post, used in the URL.
- `title`: The title of your post.
- `excerpt`: A brief summary of your post, used in previews and SEO.
- `category`: An optional category for your post.
- `tags`: An array of tags associated with your post.
- `image`: An optional image associated with your post.
- `draft`: A boolean indicating whether the post is a draft (not publicly visible).
- `createdAt`: The creation date of the post.
- `updatedAt`: The last update date of the post.

## Customizing Front Matter

While Prezet provides a default structure for front matter, you can customize it to fit your specific needs. For detailed instructions on how to extend or modify the `FrontmatterData` class, please refer to the documentation on [Customizing front matter](/customize/frontmatter).

By leveraging typed front matter, Prezet helps you maintain a robust and consistent content structure, enhancing both the reliability of your blog and the efficiency of your content management workflow.
