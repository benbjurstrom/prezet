---
title: Prezet SQLite Index
excerpt: Prezet uses an SQLite index to efficiently query and manage your markdown content. This guide explains how to manage the index, generate a sitemap, and use the document model in your custom controllers.
slug: index
date: 2024-08-20
category: Getting Started
image: /prezet/img/ogimages/index.webp
---

Prezet uses an SQLite index file to more efficiently query information about your markdown content. This index is crucial for features like pagination, sorting, and filtering of your blog posts or documentation pages.

## Managing the Index

To keep the index up-to-date with your markdown content, Prezet provides a command:

```bash
php artisan prezet:index
```

You should run this command whenever you:

1. Add a new markdown file
2. Change a markdown file's slug
3. Modify frontmatter and want to see those changes reflected on index pages

Note that changes to the main content of your markdown files don't require updating the index, as this content is read directly from the file when displaying a single post.

## Sitemap Generation
As part of the index update process, Prezet automatically generates a sitemap for your website. This feature ensures that your sitemap always reflects the most current state of your content.

For more information on sitemap generation, refer to the [Sitemap Generation](/features/sitemap) guide.

## Purpose of the Index

The Prezet index serves several key purposes:

1. **Performance**: By storing key information in a database, Prezet can quickly retrieve and display lists of posts without having to parse all markdown files for every request.

2. **Advanced Querying**: The index allows for efficient sorting, filtering, and pagination of your content based on various attributes like date, category, or tags.

3. **Separation of Concerns**: While the full markdown content remains in files (allowing for easy editing and version control), the index stores only the essential metadata needed for listing and organizing your content.

## Using the Document Model

The Prezet index is accessible via the eloquent model below. You can use this model in your custom controllers to create advanced features and functionality.

```php
use BenBjurstrom\Prezet\Models\Document;
```

For more information on customizing controllers and integrating them with Prezet, please refer to our detailed [Controllers](/customize/controllers) guide.

### Common Query Patterns

Here are some common ways you might use the Document model in your controllers:

1. **Retrieving all published documents:**

   ```php
   $publishedDocs = Document::where('draft', false)->get();
   ```

2. **Filtering by category:**

   ```php
   $categoryDocs = Document::where('category', 'your-category')->get();
   ```

3. **Sorting by date:**

   ```php
   $sortedDocs = Document::orderBy('date', 'desc')->get();
   ```

4. **Paginating results:**

   ```php
   $paginatedDocs = Document::where('draft', false)->paginate(10);
   ```

5. **Filtering by tags:**

   ```php
   $taggedDocs = Document::whereHas('tags', function($query) {
       $query->where('name', 'your-tag');
   })->get();
   ```

## Structure of the Index Database

The Prezet index is stored in an SQLite database and consists of several tables:

### Documents Table

This table stores the core information about each markdown document:

- `slug`: The URL-friendly identifier for the document
- `category`: The category of the document (if applicable)
- `draft`: A boolean indicating whether the document is a draft
- `frontmatter`: JSON-encoded frontmatter data
- `created_at`: Timestamp of when the document was created
- `updated_at`: Timestamp of when the document was last updated

### Tags Table

This table stores unique tags used across all documents:

- `id`: Unique identifier for the tag
- `name`: The name of the tag

### Document_Tags Table

This table manages the many-to-many relationship between documents and tags:

- `document_id`: ID of the document
- `tag_id`: ID of the tag
