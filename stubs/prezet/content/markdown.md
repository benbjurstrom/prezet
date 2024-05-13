---
title: Markdown Powered Blogging
date: 2024-05-08
category: Features
excerpt: Prezet is a markdown powered blogging platform that is easy to use and customize.
---

This blog is generated entirely from markdown files. When you ran the `prezet::install` command markdown files were loaded into you laravel application's `./storage/prezet/` directory. Here is the structure of those files:

```bash
storage/
└── prezet/
    ├── .obsidian/
    │   └── ...
    ├── content/
    │   ├── blade.md
    │   ├── draft.md
    │   ├── markdown.md
    │   ├── seo.md
    │   └── customize/
    │       ├── routes.md
    │       ├── frontmatter.md
    │       ├── blade-views.md
    │       └── controllers.md
    ├── images/
    │   ├── markdown-20240509210223449.webp
    │   └── ogimages
    └── SUMMARY.md
```

The `./storage/prezet/content/` directory contains the actual markdown files that are converted into html when the blog is loaded.

The `./storage/prezet/images/` directory stores the images that are referenced in the markdown files. Those images are automatically linked to a packaged controller that serves the images in the most efficient format and size based on the user's device. You can find more information about how Prezet automatically optimizes images [here](content/images)

Inside the images directory, there is a folder called `ogimages` that contains any open graph images used by the blog posts. You can learn more about using Prezet to generate open graph images [here](content/seo).

The `SUMMARY.md` file is used to generate the structure on the left side of the page. This gives you control over the order and categories in which each post appears.

## Table of Contents

If you're on desktop, you should see a table of contents on the right side of the page. This is automatically generated based on the headings in the markdown file.

## Blade Components
Much like MDX where you can include JSX components in a markdown file, Prezet lets you include blade components in your markdown files. You can read more about using blade components in markdown [here](content/blade).



[table](https://tree.nathanfriend.io/?s=(%27options!(%27fancy!true~fullPath!false~trailingSlash!true~rootDot!false)~B(%27B%27storageGprezet6.obsidianH...6content6E7draft787seo7customizeH*routes7*frontmatter7E-views7*controllersF6C*8-20240509210223449.webpHogCSUMMARYF%27)~version!%271%27)*%20%206G*7FH8markdownBsource!Cimages6E*bladeF.mdG%5Cn*H6*%01HGFECB876*)
