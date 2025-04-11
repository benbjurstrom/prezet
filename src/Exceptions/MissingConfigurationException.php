<?php

namespace Prezet\Prezet\Exceptions;

use Exception;

class MissingConfigurationException extends Exception
{
    public function __construct(string $configKey)
    {
        $message = 'Missing configuration for '.$configKey;

        parent::__construct($message);
    }
}
