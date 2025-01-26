<?php

use BenBjurstrom\Prezet\Prezet;

it('sets the ogimage frontmatter', function () {
    $result = Prezet::setOgImage('welcome-to-prezet', 'test-image.jpg');
})->throwsNoExceptions()->skip();
