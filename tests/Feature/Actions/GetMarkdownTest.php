<?php

use BenBjurstrom\Prezet\Actions\GetMarkdown;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

it('can get markdown content', function () {
    Storage::fake('prezet');
    Storage::disk('prezet')->put('content/post1.md', '# Post 1 Content');

    $markdown = GetMarkdown::handle('content/post1.md');

    expect($markdown)->toBe('# Post 1 Content');
});

it('throws 404 exception if markdown file does not exist', function () {
    Storage::fake('prezet');

    GetMarkdown::handle('non-existent-post');
})->throws(NotFoundHttpException::class);

it('throws 422 exception if slug is invalid', function () {
    Storage::fake('prezet');

    GetMarkdown::handle('invalid slug');
})->throws(HttpException::class);
