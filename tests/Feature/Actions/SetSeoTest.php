<?php

use BenBjurstrom\Prezet\Actions\SetSeo;
use BenBjurstrom\Prezet\Data\FrontmatterData;

it('sets the seo data', function () {
    $frontmatterData = new FrontmatterData([
        'title' => 'Test Title',
        'excerpt' => 'Test excerpt',
        'slug' => 'test-slug',
        'category' => 'Test Category',
        'ogimage' => 'test-image.jpg',
        'date' => 1715705878,
        'updatedAt' => 1715705878,
    ]);

    SetSeo::handle($frontmatterData);
})->throwsNoExceptions();
