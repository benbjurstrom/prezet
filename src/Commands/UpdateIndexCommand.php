<?php

namespace BenBjurstrom\Prezet\Commands;

use BenBjurstrom\Prezet\Actions\UpdateIndex;
use Illuminate\Console\Command;

class UpdateIndexCommand extends Command
{
    public $signature = 'prezet:update-index';

    public $description = 'Updates the prezet.sqlite file.';

    public function handle(): int
    {
        UpdateIndex::handle();

        return self::SUCCESS;
    }
}
