<?php

use Illuminate\Support\Facades\Storage;
use Prezet\Prezet\Prezet;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

it('can get markdown content', function () {
    Storage::fake('prezet');
    Storage::disk(config('prezet.filesystem.disk'))->put('content/post1.md', '# Post 1 Content');

    $markdown = Prezet::getMarkdown('content/post1.md');

    expect($markdown)->toBe('# Post 1 Content');
});

it('throws 404 exception if markdown file does not exist', function () {
    Storage::fake('prezet');

    Prezet::getMarkdown('non-existent-post');
})->throws(NotFoundHttpException::class);

it('throws 422 exception if slug is invalid', function () {
    Storage::fake('prezet');

    Prezet::getMarkdown('invalid slug');
})->throws(HttpException::class);
