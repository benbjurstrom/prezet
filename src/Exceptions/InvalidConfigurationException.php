<?php

namespace BenBjurstrom\Prezet\Exceptions;

use Exception;

class InvalidConfigurationException extends Exception
{
    public function __construct(string $configKey, mixed $configValue, string $deficiency)
    {
        $message = 'Invalid configuration for '.$configKey.'. The given value '.$configValue.' '.$deficiency;

        parent::__construct($message);
    }
}
