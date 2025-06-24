<?php

namespace Prezet\Prezet\Exceptions;

use Exception;

class FrontmatterException extends Exception
{
    public function __construct(string $message, string|bool $filePath)
    {
        if ($filePath === false) {
            $filePath = 'filepath unavailable';
        }

        $message = 'Frontmatter issues found in '.$filePath.'. '.$message;

        parent::__construct($message);
    }
}
