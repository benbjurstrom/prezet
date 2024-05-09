<?php

namespace BenBjurstrom\Prezet\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

class FrontmatterException extends Exception
{
    public function __construct(MessageBag $bag, string $slug)
    {
        $message = 'Frontmatter issues found in '.$slug.'.md: '.$bag->first();

        parent::__construct($message);
    }
}
