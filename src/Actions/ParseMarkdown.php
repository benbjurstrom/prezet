<?php

namespace Prezet\Prezet\Actions;

use Exception;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Output\RenderedContentInterface;
use Prezet\Prezet\Exceptions\InvalidConfigurationException;
use Prezet\Prezet\Extensions\MarkdownBladeExtension;
use Phiki\CommonMark\PhikiExtension;

class ParseMarkdown
{
    /**
     * @throws Exception|CommonMarkException
     */
    public function handle(string $md): RenderedContentInterface
    {
        $config = config('prezet.commonmark.config');
        if (! is_array($config)) {
            throw new InvalidConfigurationException('prezet.commonmark.config', $config, 'is not an array');
        }

        $environment = new Environment($config);
        $extensions = $this->getExtensions();

        foreach ($extensions as $extension) {
            if($extension === 'Phiki\CommonMark\PhikiExtension') {
                $config = config('prezet.commonmark.config.phiki');
                $environment->addExtension(new PhikiExtension(
                    $config['theme'],
                    withWrapper:$config['withWrapper'],
                    withGutter:$config['withGutter']
                ));
            }else{
                $environment->addExtension(new $extension);
            }
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
    protected function getExtensions(): array
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
