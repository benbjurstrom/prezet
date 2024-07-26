<?php

namespace BenBjurstrom\Prezet\Commands;

use BenBjurstrom\Prezet\Actions\GetAllFrontmatter;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\note;

class ValidateFrontmatterCommand extends Command
{
    public $signature = 'prezet:validate-frontmatter';

    public $description = 'Checks that each post passes frontmatter validation';

    public function handle(): int
    {
        $result = GetAllFrontmatter::handle();
        note('Validating frontmatter for '.count($result).' posts');
        info('All posts passed frontmatter validation!');

        return self::SUCCESS;
    }
}
