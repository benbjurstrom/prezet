---
title: 'This post has a YouTube video'
date: 2024-05-02
category: Features
excerpt: 'This post contains an embedded youtube video rendered via a blade component.'
---

Note the embedded youtube video below

```html +parse
<x-prezet::youtube videoid="dt1ado9wJi8" title="Supercharge Markdown with Laravel">
    Login
</x-prezet::youtube>
```

If you inspect the storage/prezet/content/youtube.md file you'll see the following code snippet referencing a blade component.

```html -parse
<x-prezet::youtube videoid="dt1ado9wJi8" title="Supercharge Markdown with Laravel">
    Login
</x-prezet::youtube>
```
