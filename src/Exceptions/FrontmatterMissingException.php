<?php

namespace BenBjurstrom\Prezet\Exceptions;

use Exception;

class FrontmatterMissingException extends Exception
{
    public function __construct(string $filePath)
    {
        $message = 'Frontmatter missing in '.$filePath;

        parent::__construct($message);
    }
}
