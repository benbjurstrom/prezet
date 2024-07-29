<?php

use BenBjurstrom\Prezet\Actions\SetOgImage;

it('sets the ogimage frontmatter', function () {
    $result = SetOgImage::handle('welcome-to-prezet', 'test-image.jpg');
})->throwsNoExceptions()->skip();
