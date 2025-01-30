<?php

namespace BenBjurstrom\Prezet;


use Illuminate\Support\Facades\Facade;

class Prezet extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PrezetService::class;
    }
}
