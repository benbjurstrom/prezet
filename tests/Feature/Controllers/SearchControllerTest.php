<?php

namespace BenBjurstrom\Prezet\Tests\Feature\Controllers;

use BenBjurstrom\Prezet\Http\Controllers\SearchController;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Models\Heading;
use Illuminate\Foundation\Testing\RefreshDatabase;
use BenBjurstrom\Prezet\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class SearchControllerTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seedTestData();

        Route::get('prezet/search', SearchController::class)->name('prezet.search');
    }

    public function test_search_returns_expected_results(): void
    {
        $response = $this->getJson(route('prezet.search', [
            'q' => 'Laravel'
        ]));


        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => ['slug', 'title', 'excerpt', 'category', 'tags', 'image', 'draft', 'createdAt', 'updatedAt']
            ])
            ->assertJsonFragment(['title' => 'Introduction to Laravel'])
            ->assertJsonFragment(['title' => 'Laravel Best Practices']);
    }

    public function test_search_returns_empty_results_for_non_matching_query(): void
    {
        $response = $this->getJson(route('prezet.search', [
            'q' => 'NonExistentTerm'
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }

    public function test_search_validates_query_parameter(): void
    {
        $response = $this->getJson(route('prezet.search'));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['q']);
    }

    private function seedTestData(): void
    {
        $documents = [
            [
                'slug' => 'intro-to-laravel',
                'category' => 'Web Development',
                'draft' => false,
                'frontmatter' => [
                    'title' => 'Introduction to Laravel',
                    'excerpt' => 'Learn the basics of Laravel framework',
                    'tags' => ['PHP', 'Laravel', 'Framework'],
                    'image' => null,
                ],
            ],
            [
                'slug' => 'laravel-best-practices',
                'category' => 'Web Development',
                'draft' => false,
                'frontmatter' => [
                    'title' => 'Laravel Best Practices',
                    'excerpt' => 'Discover best practices for Laravel development',
                    'tags' => ['PHP', 'Laravel', 'Best Practices'],
                    'image' => null,
                ],
            ],
            [
                'slug' => 'vue-js-basics',
                'category' => 'Frontend',
                'draft' => false,
                'frontmatter' => [
                    'title' => 'Vue.js Basics',
                    'excerpt' => 'Learn the fundamentals of Vue.js',
                    'tags' => ['JavaScript', 'Vue.js', 'Frontend'],
                    'image' => null,
                ],
            ],
        ];

        foreach ($documents as $doc) {
            $document = Document::create([
                'slug' => $doc['slug'],
                'category' => $doc['category'],
                'draft' => $doc['draft'],
                'frontmatter' => json_encode($doc['frontmatter']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Heading::create([
                'document_id' => $document->id,
                'text' => $doc['frontmatter']['title'],
                'level' => 1,
                'section' => 0,
            ]);
        }
    }
}
