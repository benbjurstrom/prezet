---
title: JSON-LD in Prezet
date: 2025-01-26
category: Features
excerpt: Learn how to add JSON-LD structured data to your Prezet blog for improved SEO and accessibility.
image: /prezet/img/ogimages/features-jsonld.webp
---

Prezet automatically generates [JSON-LD](https://json-ld.org/) to enhance your site's structured data, improving visibility on search engines and social media. By default, Prezet includes metadata such as your article's headline, publishing date, and author information following the [Google Article Guide](https://developers.google.com/search/docs/appearance/structured-data/article).

## How It Works

1. **Front Matter**: Prezet extracts key information—like `title`, `date`, `excerpt`, `image`, and `author`—from your front matter.  
2. **Linked Data Action**: This data is then passed through the `GetLinkedData` action, which compiles your post’s structured data in a JSON-LD format.  
3. **Blade Template**: The JSON-LD is injected into the `<head>` section in `resources/views/vendor/prezet/show.blade.php` via a simple script tag.

Below is a snippet from the default `show.blade.php` that demonstrates how the JSON-LD is loaded:

```php
{{-- show.blade.php --}}
@push('jsonld')
    <script type="application/ld+json">{!! $linkedData !!}</script>
@endpush
```

## Default JSON-LD Output

Out of the box, the JSON-LD includes:

- **Article Title** (`headline`): Pulled from the `title` in front matter.
- **Publication Date** (`datePublished`): Derived from the front matter `date` or file modification date.
- **Modified Date** (`dateModified`): Reflects when the document was last updated.
- **Author** (`author`): Controlled by your [front matter](/customize/frontmatter) and the `'authors'` array in `config/prezet.php`.
- **Publisher** (`publisher`): Defined in the `'publisher'` array of `config/prezet.php`.
- **Image** (`image`): Uses the front matter `image` field if set; otherwise defaults to your publisher's `image`.

Here is an example of what the JSON-LD might look like in your rendered HTML:

```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "My Awesome Blog Post",
  "datePublished": "2024-06-30T12:00:00+00:00",
  "dateModified": "2024-07-01T10:15:27+00:00",
  "author": {
    "@type": "Person",
    "name": "Jane Doe",
    "url": "https://jane.example.com",
    "image": "https://jane.example.com/avatar.jpg"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Prezet",
    "url": "https://prezet.com",
    "logo": "https://prezet.com/favicon.svg",
    "image": "https://prezet.com/ogimage.png"
  },
  "image": "https://jane.example.com/my-featured-image.webp"
}
```

## Customizing Your JSON-LD

### Front Matter & Author Fields

Prezet uses the front matter’s `author` field to decide which author profile to link. If you’d like to store multiple author definitions (e.g., for a multi-author blog), add them to the `'authors'` array in `config/prezet.php`:

```php
'authors' => [
    'jane_doe' => [
        '@type' => 'Person',
        'name' => 'Jane Doe',
        'url' => 'https://jane.example.com',
        'image' => 'https://jane.example.com/avatar.jpg',
    ],
    'john_smith' => [
        '@type' => 'Person',
        'name' => 'John Smith',
        'url' => 'https://john.example.com',
        'image' => 'https://john.example.com/avatar.jpg',
    ],
],
```

Then, in your markdown:

```yaml
---
title: My Awesome Blog Post
author: jane_doe
date: 2024-06-30
image: /blog-images/featured.webp
excerpt: This is a summary of my awesome blog post.
---
```

### Publisher Settings

If you’d like to change your site or company details, update the `'publisher'` array in `config/prezet.php`:

```php
'publisher' => [
    '@type' => 'Organization',
    'name' => 'My Company Name',
    'url' => 'https://mycompany.example.com',
    'logo' => 'https://mycompany.example.com/logo.svg',
    'image' => 'https://mycompany.example.com/ogimage.png',
],
```

### Overriding the `GetLinkedData` Action

For advanced changes—like adding custom properties or adjusting how fields are merged—you can override the `GetLinkedData` action:

1. **Create Your Action**
   ```php
   // app/Actions/CustomGetLinkedData.php
   namespace App\Actions;

   use BenBjurstrom\Prezet\Actions\GetLinkedData;
   use BenBjurstrom\Prezet\Data\DocumentData;

   class CustomGetLinkedData extends GetLinkedData
   {
       public function handle(DocumentData $document): array
       {
           $jsonLd = parent::handle($document);

           // Add your own fields or logic
           $jsonLd['isPartOf'] = [
               '@type' => 'Blog',
               'name'  => 'My Custom Blog',
           ];

           return $jsonLd;
       }
   }
   ```

2. **Bind Your Action**
   ```php
   // app/Providers/AppServiceProvider.php
   namespace App\Providers;

   use Illuminate\Support\ServiceProvider;
   use BenBjurstrom\Prezet\Actions\GetLinkedData;
   use App\Actions\CustomGetLinkedData;

   class AppServiceProvider extends ServiceProvider
   {
       public function register(): void
       {
           $this->app->bind(GetLinkedData::class, CustomGetLinkedData::class);
       }
   }
   ```

Prezet will now resolve your custom version of the action whenever structured data is generated.

## Verifying Your Structured Data

Once your page is live, you can use Google’s [Rich Results Test](https://search.google.com/test/rich-results) or other structured data testing tools to confirm that your JSON-LD is recognized and valid. Properly formed structured data can improve your site’s appearance in search results and increase user engagement.

---

**Next Steps**
- To learn more about customizing other aspects of Prezet, see the [Controllers](/customize/controllers) or [Actions](/customize/actions) documentation.
- Explore additional ways to add or modify metadata via the [Front Matter](/customize/frontmatter) guide.
- For more details on how Prezet handles SEO and meta tags, head over to the [SEO Features](/features/seo) page.
