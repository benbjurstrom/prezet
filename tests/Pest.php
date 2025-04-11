<?php

use Prezet\Prezet\Tests\TestCase;

uses(
    TestCase::class,
    // Illuminate\Foundation\Testing\RefreshDatabase::class
    // Illuminate\Foundation\Testing\DatabaseMigrations::class
)->in(__DIR__);

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function blade(string $blade): string
{
    return eval('ob_start(); ?>'.app('blade.compiler')->compileString($blade).' <?php return trim(ob_get_clean());');
}

function meta(): string
{
    return view('prezet::components.meta')->render();
}
