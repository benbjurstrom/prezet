<?php

namespace BenBjurstrom\Prezet\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BenBjurstrom\Prezet\Prezet
 */
class Prezet extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \BenBjurstrom\Prezet\Prezet::class;
    }
}
