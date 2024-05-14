<?php

use BenBjurstrom\Prezet\Actions\SetSeo;
use BenBjurstrom\Prezet\Data\FrontmatterData;

it('sets the seo data', function () {
    $frontmatterData = new FrontmatterData([
        'title' => 'Test Title',
        'excerpt' => 'Test excerpt',
        'slug' => 'test-slug',
        'date' => 1715705878,
        'category' => 'Test Category',
        'ogimage' => 'test-image.jpg',
    ]);

    SetSeo::handle($frontmatterData);
})->throwsNoExceptions();
