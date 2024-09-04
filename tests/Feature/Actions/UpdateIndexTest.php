<?php

use BenBjurstrom\Prezet\Actions\UpdateIndex;
use BenBjurstrom\Prezet\Http\Controllers\ImageController;
use BenBjurstrom\Prezet\Http\Controllers\ShowController;
use Illuminate\Support\Facades\Route;

it('updates the index', function () {
    Route::get('prezet/img/{path}', ImageController::class)
        ->name('prezet.image')
        ->where('path', '.*');

    Route::get('prezet/{slug}', ShowController::class)
        ->name('prezet.show')
        ->where('slug', '.*');

    UpdateIndex::handle();
})->throwsNoExceptions();
