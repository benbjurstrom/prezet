<?php

namespace Prezet\Prezet;

use Illuminate\Support\Facades\Blade;
use Prezet\Prezet\Actions\CreateIndex;
use Prezet\Prezet\Actions\GenerateOgImage;
use Prezet\Prezet\Actions\GetDocumentDataFromFile;
use Prezet\Prezet\Actions\GetDocumentDataFromFiles;
use Prezet\Prezet\Actions\GetDocumentModelFromSlug;
use Prezet\Prezet\Actions\GetFlatHeadings;
use Prezet\Prezet\Actions\GetHeadings;
use Prezet\Prezet\Actions\GetImage;
use Prezet\Prezet\Actions\GetLinkedData;
use Prezet\Prezet\Actions\GetMarkdown;
use Prezet\Prezet\Actions\GetPrezetDisk;
use Prezet\Prezet\Actions\GetSlugFromFilepath;
use Prezet\Prezet\Actions\GetSummary;
use Prezet\Prezet\Actions\ParseFrontmatter;
use Prezet\Prezet\Actions\ParseMarkdown;
use Prezet\Prezet\Actions\SearchHeadings;
use Prezet\Prezet\Actions\SetFrontmatter;
use Prezet\Prezet\Actions\SetKey;
use Prezet\Prezet\Actions\SetOgImage;
use Prezet\Prezet\Actions\UpdateIndex;
use Prezet\Prezet\Actions\UpdateSitemap;
use Prezet\Prezet\Commands\InstallCommand;
use Prezet\Prezet\Commands\OgimageCommand;
use Prezet\Prezet\Commands\PurgeCacheCommand;
use Prezet\Prezet\Commands\UpdateIndexCommand;
use Prezet\Prezet\Data\DocumentData;
use Prezet\Prezet\Data\FrontmatterData;
use Prezet\Prezet\Data\HeadingData;
use Prezet\Prezet\Data\YoutubeData;
use Prezet\Prezet\Models\Document;
use Prezet\Prezet\Models\Heading;
use Prezet\Prezet\Models\Tag;
use Prezet\Prezet\Services\Seo;
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
        GetDocumentDataFromFiles::class => GetDocumentDataFromFiles::class,
        GetDocumentDataFromFile::class => GetDocumentDataFromFile::class,
        GetFlatHeadings::class => GetFlatHeadings::class,
        GetHeadings::class => GetHeadings::class,
        GetImage::class => GetImage::class,
        GetLinkedData::class => GetLinkedData::class,
        GetMarkdown::class => GetMarkdown::class,
        GetPrezetDisk::class => GetPrezetDisk::class,
        GetSlugFromFilepath::class => GetSlugFromFilepath::class,
        GetSummary::class => GetSummary::class,
        ParseFrontmatter::class => ParseFrontmatter::class,
        ParseMarkdown::class => ParseMarkdown::class,
        SetFrontmatter::class => SetFrontmatter::class,
        SetOgImage::class => SetOgImage::class,
        UpdateIndex::class => UpdateIndex::class,
        UpdateSitemap::class => UpdateSitemap::class,
        SearchHeadings::class => SearchHeadings::class,
        SetKey::class => SetKey::class,
        GetDocumentModelFromSlug::class => GetDocumentModelFromSlug::class,

        /**
         * Data Class Bindings
         */
        DocumentData::class => DocumentData::class,
        HeadingData::class => HeadingData::class,
        FrontmatterData::class => FrontmatterData::class,
        YoutubeData::class => YoutubeData::class,

        /**
         * Model Class Bindings
         */
        Document::class => Document::class,
        Heading::class => Heading::class,
        Tag::class => Tag::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->app->scoped('seo', Seo::class);
    }

    public function boot(): void
    {
        parent::boot();

        Blade::directive('seo', function ($expression) {
            return "<?php echo seo()->render($expression); ?>";
        });
    }

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
