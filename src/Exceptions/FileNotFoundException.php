<?php

namespace BenBjurstrom\Prezet\Exceptions;

use Exception;

class FileNotFoundException extends Exception
{
    public function __construct(string $filePath)
    {
        $message = 'File not found at '.$filePath;

        parent::__construct($message);
    }
}
