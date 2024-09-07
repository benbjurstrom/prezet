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


        dd($response->json());

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
        $document = Document::factory()->create([
            'slug' => 'test-slug',
            'category' => 'Test Category',
        ]);


        Heading::create([
            'document_id' => $document->id,
            'text' => 'Introduction to Laravel',
            'level' => 1,
            'section' => 0,
        ]);
    }
}
