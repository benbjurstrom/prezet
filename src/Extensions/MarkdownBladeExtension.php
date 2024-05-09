<?php

namespace BenBjurstrom\Prezet\Extensions;

use Illuminate\Support\Facades\Blade;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentRenderedEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

class MarkdownBladeExtension implements ExtensionInterface, NodeRendererInterface
{
    public static bool $allowBladeForNextDocument = false;

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, $this, 100);
        $environment->addEventListener(DocumentRenderedEvent::class, [$this, 'onDocumentRenderedEvent']);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): ?string
    {
        if (! static::$allowBladeForNextDocument) {
            return null;
        }

        /** @var FencedCode $node */
        $info = $node->getInfoWords();

        // Look for our magic word
        if (in_array('+parse', $info)) {
            return Blade::render($node->getLiteral());
        }

        return null;
    }

    public function onDocumentRenderedEvent()
    {
        static::$allowBladeForNextDocument = false;
    }
}
