<?php

namespace BenBjurstrom\Prezet;

use BenBjurstrom\Prezet\Actions\CreateIndex;
use BenBjurstrom\Prezet\Actions\GenerateOgImage;
use BenBjurstrom\Prezet\Actions\GetAllDocsFromFiles;
use BenBjurstrom\Prezet\Actions\GetDocFromFile;
use BenBjurstrom\Prezet\Actions\GetFlatHeadings;
use BenBjurstrom\Prezet\Actions\GetHeadings;
use BenBjurstrom\Prezet\Actions\GetImage;
use BenBjurstrom\Prezet\Actions\GetLinkedData;
use BenBjurstrom\Prezet\Actions\GetMarkdown;
use BenBjurstrom\Prezet\Actions\GetPrezetDisk;
use BenBjurstrom\Prezet\Actions\GetSummary;
use BenBjurstrom\Prezet\Actions\ParseFrontmatter;
use BenBjurstrom\Prezet\Actions\ParseMarkdown;
use BenBjurstrom\Prezet\Actions\SearchHeadings;
use BenBjurstrom\Prezet\Actions\SetFrontmatter;
use BenBjurstrom\Prezet\Actions\SetOgImage;
use BenBjurstrom\Prezet\Actions\UpdateIndex;
use BenBjurstrom\Prezet\Actions\UpdateSitemap;
use BenBjurstrom\Prezet\Commands\InstallCommand;
use BenBjurstrom\Prezet\Commands\OgimageCommand;
use BenBjurstrom\Prezet\Commands\PurgeCacheCommand;
use BenBjurstrom\Prezet\Commands\UpdateIndexCommand;
use BenBjurstrom\Prezet\Data\DocumentData;
use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Data\HeadingData;
use BenBjurstrom\Prezet\Data\YoutubeData;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PrezetServiceProvider extends PackageServiceProvider
{
    /**
     * @var array<string, string>
     */
    public array $bindings = [
        /**
         * Action Class Bindings
         */
        CreateIndex::class => CreateIndex::class,
        GenerateOgImage::class => GenerateOgImage::class,
        GetAllDocsFromFiles::class => GetAllDocsFromFiles::class,
        GetDocFromFile::class => GetDocFromFile::class,
        GetFlatHeadings::class => GetFlatHeadings::class,
        GetHeadings::class => GetHeadings::class,
        GetImage::class => GetImage::class,
        GetLinkedData::class => GetLinkedData::class,
        GetMarkdown::class => GetMarkdown::class,
        GetPrezetDisk::class => GetPrezetDisk::class,
        GetSummary::class => GetSummary::class,
        ParseFrontmatter::class => ParseFrontmatter::class,
        ParseMarkdown::class => ParseMarkdown::class,
        SetFrontmatter::class => SetFrontmatter::class,
        SetOgImage::class => SetOgImage::class,
        UpdateIndex::class => UpdateIndex::class,
        UpdateSitemap::class => UpdateSitemap::class,
        SearchHeadings::class => SearchHeadings::class,

        /**
         * Data Class Bindings
         */
        DocumentData::class => DocumentData::class,
        HeadingData::class => HeadingData::class,
        FrontmatterData::class => FrontmatterData::class,
        YoutubeData::class => YoutubeData::class,
    ];

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('prezet')
            ->hasConfigFile()
            ->hasViews('prezet')
            ->hasMigrations([
                'create_prezet_documents_table',
                'create_prezet_document_tags_table',
                'create_prezet_tags_table',
                'create_prezet_headings_table',
            ])
            ->hasCommands([
                InstallCommand::class,
                OgimageCommand::class,
                PurgeCacheCommand::class,
                UpdateIndexCommand::class,
            ]);
    }
}
