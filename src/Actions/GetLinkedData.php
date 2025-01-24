<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\DocumentData;
use BenBjurstrom\Prezet\Data\FrontmatterData;
use Illuminate\Support\Facades\Config;

class GetLinkedData
{
    /**
     * @return array<string, string|array<string, string>>
     */
    public static function handle(DocumentData $document): array
    {
        $fm = $document->frontmatter;
        $author = self::getAuthor($fm);
        $image = self::getImage($fm);
        $publisher = Config::array('prezet.publisher');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $fm->title,
            'datePublished' => $document->createdAt->toIso8601String(),
            'dateModified' => $document->updatedAt->toIso8601String(),
            'author' => $author,
            'publisher' => $publisher,
            'image' => $image,
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function getAuthor(FrontmatterData $fm): array
    {
        $authors = Config::array('prezet.authors');

        // If frontmatter has an author and it exists in the config, use it
        if (isset($fm->author) && isset($authors[$fm->author])) {
            return $authors[$fm->author];
        }

        // Otherwise return the first author from the config
        return reset($authors);
    }

    private static function getImage(FrontmatterData $fm): string
    {
        $publisher = Config::array('prezet.publisher');

        // If no image in frontmatter, use publisher ogimage
        if (empty($fm->image)) {
            return $publisher['image'];
        }

        $image = $fm->image;

        // Check if image already has an origin (scheme://host:port)
        if (! preg_match('/^[a-z]+:\/\/[^\/]+/i', $image)) {
            // No origin found, prepend publisher url (without trailing path)
            $publisherOrigin = preg_replace('/^(https?:\/\/[^\/]+).*$/', '$1', $publisher['url']);

            return $publisherOrigin.$image;
        }

        return $image;
    }
}
