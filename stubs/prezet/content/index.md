---
title: Prezet SQLite Index
excerpt: Learn about the SQLite index used by Prezet to manage and query markdown content.
date: 2024-06-27
category: Getting Started
image: /prezet/img/ogimages/index.png
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

```bash
php artisan prezet:index --fresh
```

Prezet index also has a `--fresh` option that will create a new sqlite database and run the prezet migrations before inserting your markdown data. You should run this command whenever you:

1. Update to a new version of Prezet
2. Are creating an index in a CI/CD pipeline
3. Deploy your application to an environment where the index sqlite file is not already present

### Automatically Updating the Index

You can also use [Vite](https://vite.dev/) to watch for changes to your markdown files and automatically update the index. To start the watcher, run:

```bash
npm run dev
```

Note that if you change the folder name or location, make sure to update the relevant paths in your vite.config.js file so that Vite continues to monitor your files properly.

## Sitemap Generation
As part of the index update process, Prezet automatically generates a sitemap for your website. This feature ensures that your sitemap always reflects the most current state of your content.

For more information on sitemap generation, refer to the [Sitemap Generation](/features/sitemap) guide.

## Purpose of the Index

The Prezet index serves several key purposes:

1. **Performance**: By storing key information in a database, Prezet can quickly retrieve and display lists of posts without having to parse all markdown files for every request.

2. **Advanced Querying**: The index allows for efficient sorting, filtering, and pagination of your content based on various attributes like date, category, or tags.

3. **Separation of Concerns**: While the full markdown content remains in files (allowing for easy editing and version control), the index stores only the essential metadata needed for listing and organizing your content.

## Structure of the Index Database

The Prezet index is stored in an SQLite database and consists of several tables:

### Documents Table

This table stores the core information about each markdown document:

- `id`: Auto-incrementing primary key
- `key`: Optional unique identifier that can be used in front matter
- `slug`: The URL-friendly identifier for the document (unique)
- `filepath`: The path to the markdown file relative to the content root (unique)
- `category`: The category of the document (if applicable)
- `draft`: A boolean indicating whether the document is a draft
- `hash`: MD5 hash of the file contents for change detection (unique)
- `frontmatter`: JSON-encoded frontmatter data
- `created_at`: Timestamp of when the document was created
- `updated_at`: Timestamp of when the document was last updated

The table includes indexes on all key fields to optimize query performance, including:
- Single column indexes on: `key`, `slug`, `filepath`, `category`, `draft`, `hash`, `created_at`, `updated_at`
- A composite index on `filepath` and `hash`

### Tags Table

This table stores unique tags used across all documents:

- `id`: Unique identifier for the tag
- `name`: The name of the tag

### Document_Tags Table

This table manages the many-to-many relationship between documents and tags:

- `document_id`: ID of the document
- `tag_id`: ID of the tag

## Using the Document Model

The Prezet index is accessible via the Document model. You can use this model in your custom controllers to create advanced features and functionality.

```php
use BenBjurstrom\Prezet\Models\Document;
```

### Common Query Patterns

Here are some common ways you might use the Document model in your controllers:

1. **Retrieving all published documents:**

   ```php
   $publishedDocs = Document::where('draft', false)->get();
   ```

2. **Finding a document by slug:**

   ```php
   $doc = Document::where('slug', $slug)->firstOrFail();
   ```

3. **Finding a document by key:**

   ```php
   $doc = Document::where('key', $key)->firstOrFail();
   ```

4. **Filtering by category:**

   ```php
   $categoryDocs = Document::where('category', 'your-category')->get();
   ```

5. **Sorting by date:**

   ```php
   $sortedDocs = Document::orderBy('created_at', 'desc')->get();
   ```
