---
title: 'Include Blade Components in Markdown'
date: 2024-05-07
category: Features
excerpt: 'This post contains an embedded youtube video rendered via a blade component.'
---

Note the embedded YouTube video below

```html +parse
<x-prezet::youtube videoid="dt1ado9wJi8" title="Supercharge Markdown with Laravel">
    Login
</x-prezet::youtube>
```

If you inspect the storage/prezet/content/youtube.md file you'll see the following code snippet referencing the `prezet::youtube` blade component.

![](blade-20240511224634030.webp)

When the markdown file is parsed, the blade component is rendered and inserted into the HTML markup.
