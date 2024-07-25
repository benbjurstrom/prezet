<?php

use BenBjurstrom\Prezet\Actions\SetOgImage;
use BenBjurstrom\Prezet\Actions\SetSeo;
use BenBjurstrom\Prezet\Data\FrontmatterData;

it('sets the ogimage frontmatter', function () {
    $result = SetOgImage::handle('welcome-to-prezet', 'test-image.jpg');
})->throwsNoExceptions()->skip();
