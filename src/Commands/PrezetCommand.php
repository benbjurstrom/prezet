<?php

namespace BenBjurstrom\Prezet\Commands;

use Illuminate\Console\Command;

class PrezetCommand extends Command
{
    public $signature = 'prezet';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
