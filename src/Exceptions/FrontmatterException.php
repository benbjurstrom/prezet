<?php

namespace BenBjurstrom\Prezet\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

class FrontmatterException extends Exception
{
    public function __construct(MessageBag $bag, string|bool $slug)
    {
        if ($slug === false) {
            $slug = 'unknown';
        }

        $message = 'Frontmatter issues found in '.$slug.'.md: '.$bag->first();

        parent::__construct($message);
    }
}
