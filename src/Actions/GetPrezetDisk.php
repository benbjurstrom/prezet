<?php

namespace Prezet\Prezet\Actions;

use Prezet\Prezet\Exceptions\MissingConfigurationException;

class GetPrezetDisk
{
    /**
     * @throws MissingConfigurationException
     */
    public function handle(): string
    {
        $key = 'prezet.filesystem.disk';
        $disk = config('prezet.filesystem.disk');
        if (! is_string($disk)) {
            throw new MissingConfigurationException($key);
        }

        return $disk;
    }
}
