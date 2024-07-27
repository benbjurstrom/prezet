<?php

namespace BenBjurstrom\Prezet\Commands;

use BenBjurstrom\Prezet\Actions\UpdateIndex;
use BenBjurstrom\Prezet\Exceptions\FrontmatterException;
use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use Illuminate\Console\Command;

class UpdateIndexCommand extends Command
{
    public $signature = 'prezet:index';

    public $description = 'Updates the prezet.sqlite file.';

    public function handle(): int
    {
        try {
            UpdateIndex::handle();
        } catch (FrontmatterMissingException $e) {
            $this->error($e->getMessage());
        } catch (FrontmatterException $e) {
            $this->error($e->getMessage());
        }

        return self::SUCCESS;
    }
}
