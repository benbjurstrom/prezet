<?php

use BenBjurstrom\Prezet\Actions\UpdateIndex;

it('updates the index', function () {
    UpdateIndex::handle();
})->throwsNoExceptions()->skip();
