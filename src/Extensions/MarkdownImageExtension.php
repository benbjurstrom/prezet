<?php

namespace BenBjurstrom\Prezet\Extensions;

use Illuminate\Support\Str;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\ExtensionInterface;

class MarkdownImageExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addEventListener(DocumentParsedEvent::class, [$this, 'onDocumentParsed']);
    }

    public function onDocumentParsed(DocumentParsedEvent $event): void
    {
        $walker = $event->getDocument()->walker();

        while ($event = $walker->next()) {
            $node = $event->getNode();

            if (! $node instanceof Image || ! $event->isEntering()) {
                continue;
            }

            // if this isn't an external link, set the prefix
            if (! Str::startsWith($node->getUrl(), 'http')) {
                $originalUrl = $node->getUrl();
                $node->setUrl(config('prezet.image.path').$originalUrl);

                // Generate the srcset attribute
                $srcset = $this->generateSrcset($originalUrl);
                $node->data->set('attributes', [
                    'srcset' => $srcset,
                    'sizes' => config('prezet.image.sizes'),
                ]);
                // If the viewport is less than 1024px, the image will take up 92% of the viewport width. Otherwise, the image will be 768px wide.
                // https://coderpad.io/blog/development/the-definitive-guide-to-responsive-images-on-the-web/#:~:text=Adding%20the%20sizes%20attribute
            }
        }
    }

    private function generateSrcset(string $url): string
    {
        $srcset = [];
        $allowedSizes = config('prezet.image.widths');

        foreach ($allowedSizes as $size) {
            $srcset[] = $this->generateImageUrl($url, $size).' '.$size.'w';
        }

        return implode(', ', $srcset);
    }

    private function generateImageUrl(string $url, int $width): string
    {
        // Generate the image URL for the specified width
        $resizedUrl = config('prezet.image.path').pathinfo($url, PATHINFO_FILENAME).'-'.$width.'w.'.pathinfo($url, PATHINFO_EXTENSION);

        return $resizedUrl;
    }
}
