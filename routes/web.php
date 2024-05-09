<?php

use BenBjurstrom\Prezet\Http\Controllers\ImageController;
use BenBjurstrom\Prezet\Http\Controllers\IndexController;
use BenBjurstrom\Prezet\Http\Controllers\OgimageController;
use BenBjurstrom\Prezet\Http\Controllers\ShowController;
use Illuminate\Support\Facades\Route;

Route::get(config('prezet.image.path').'{path}', ImageController::class)
    ->name('prezet.image');

Route::get('/prezet/ogimage/{slug}', OgimageController::class)
    ->name('prezet.ogimage');

Route::get(config('prezet.path'), IndexController::class)
    ->name('prezet.index');

Route::get(config('prezet.path').'/{slug}', ShowController::class)
    ->name('prezet.show')
    ->where('slug', '.*');
// https://laravel.com/docs/11.x/routing#parameters-encoded-forward-slashes
