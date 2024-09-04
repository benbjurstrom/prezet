<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Exceptions\InvalidConfigurationException;
use BenBjurstrom\Prezet\Extensions\MarkdownBladeExtension;
use Exception;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Output\RenderedContentInterface;

class ParseMarkdown
{
    /**
     * @throws Exception|CommonMarkException
     */
    public static function handle(string $md): RenderedContentInterface
    {
        $config = config('prezet.commonmark.config');
        if (! is_array($config)) {
            throw new InvalidConfigurationException('prezet.commonmark.config', $config, 'is not an array');
        }

        $environment = new Environment($config);
        $extensions = self::getExtensions();

        foreach ($extensions as $extension) {
            $environment->addExtension(new $extension);
        }

        $converter = new MarkdownConverter($environment);

        MarkdownBladeExtension::$allowBladeForNextDocument = true;
        $result = $converter->convert($md);
        MarkdownBladeExtension::$allowBladeForNextDocument = false;

        return $result;
    }

    /**
     * @return array<int, ExtensionInterface>
     *
     * @throws InvalidConfigurationException
     */
    protected static function getExtensions(): array
    {
        $extensions = config('prezet.commonmark.extensions');
        if (! is_array($extensions)) {
            throw new InvalidConfigurationException('prezet.commonmark.extensions', $extensions, 'is not an array');
        }

        foreach ($extensions as $extension) {
            if (! is_string($extension) || ! is_subclass_of($extension, ExtensionInterface::class)) {
                throw new InvalidConfigurationException('prezet.commonmark.extensions', $extension, 'does not implement League\CommonMark\Extension\ExtensionInterface');
            }
        }

        return $extensions;
    }
}
