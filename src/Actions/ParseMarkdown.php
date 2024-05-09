<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Extensions\MarkdownBladeExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Output\RenderedContentInterface;

class ParseMarkdown
{
    public static function handle(string $md): RenderedContentInterface
    {
        $config = config('prezet.commonmark.config');
        $environment = new Environment($config);

        $extensions = config('prezet.commonmark.extensions');
        foreach ($extensions as $extension) {
            $environment->addExtension(new $extension);
        }

        $converter = new MarkdownConverter($environment);

        MarkdownBladeExtension::$allowBladeForNextDocument = true;
        $result = $converter->convert($md);
        MarkdownBladeExtension::$allowBladeForNextDocument = false;

        return $result;
    }
}
