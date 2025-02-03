---
title: Render Blade Components in Markdown
date: 2024-05-07
category: Features
excerpt: Learn how to enhance your Markdown content with dynamic Blade components in Prezet.
image: /prezet/img/ogimages/features-blade.webp
---

Prezet allows you to seamlessly integrate Blade components into your Markdown files, enabling you to create dynamic, interactive elements within your static content. 

## The MarkdownBladeExtension
This feature is powered by the [MarkdownBladeExtension](https://github.com/benbjurstrom/prezet/blob/main/src/Extensions/MarkdownBladeExtension.php). A custom CommonMark extension that's included in the Prezet package. 

The extension looks for fenced code blocks in your Markdown that include the `+parse` info word. When it finds such a block, it renders the content as a Blade component and includes the result in the final HTML output.

This extension was heavily inspired by Aaron Francis's [Blog Post](https://aaronfrancis.com/2023/rendering-blade-components-in-markdown-e2e74e55) and related YouTube video.

```html +parse
<x-prezet::youtube videoid="dt1ado9wJi8" title="Supercharge Markdown with Laravel" date="2023-12-15T12:00:00+08:00"/>
```

## YouTube Blade Component

The YouTube video above was rendered from a blade component referenced in the markdown document for this page by inlining the following code block:

    ```html +parse
    <x-prezet::youtube videoid="dt1ado9wJi8" title="Supercharge Markdown with Laravel"/>
    ```

You can view the source code for the `YouTube` component in the package's [youtube.blade.php](https://github.com/benbjurstrom/prezet/blob/main/resources/views/components/youtube.blade.php) file

## Creating Custom Components

You can create your own Blade components to use in your Markdown files. Here's a basic example:

1. Create a new Blade component:

```bash
php artisan make:component Alert
```

2. Define your component in `app/View/Components/Alert.php`:

```php
namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public $type;
    public $message;

    public function __construct($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    public function render()
    {
        return view('components.alert');
    }
}
```

3. Create the component view in `resources/views/components/alert.blade.php`:

```html
<div class="alert alert-{{ $type }}">
    {{ $message }}
</div>
```

4. Use your component in Markdown:

This feature allows you to create rich, interactive content while maintaining the simplicity and readability of Markdown.

    ```html +parse
    <x-alert type="warning" message="This is a warning message!" />
    ```

## Configuration

The MarkdownBladeExtension is enabled by default in Prezet. If you need to disable or customize its behavior, you can do so in the `config/prezet.php` file:

```php
'commonmark' => [
    'extensions' => [
        // ... other extensions
        BenBjurstrom\Prezet\Extensions\MarkdownBladeExtension::class,
    ],
    // ... other config options
],
```
