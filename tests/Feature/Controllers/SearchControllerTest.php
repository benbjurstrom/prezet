<?php

namespace BenBjurstrom\Prezet\Tests\Feature\Controllers;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Models\Heading;
use BenBjurstrom\Prezet\Tests\TestCase;

class SearchControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seedTestData();
    }

    public function test_search_returns_expected_results(): void
    {
        $response = $this->getJson(route('prezet.search', [
            'q' => 'Laravel',
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'level' => 1,
                'documentId' => 1,
                'section' => 'Introduction to Laravel',
                'text' => 'Introduction to Laravel',
                'slug' => 'intro-to-laravel',
            ]);
    }

    public function test_search_returns_empty_results_for_non_matching_query(): void
    {
        $response = $this->getJson(route('prezet.search', [
            'q' => 'NonExistentTerm',
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
                'frontmatter' => FrontmatterData::fromArray([
                    'slug' => 'intro-to-laravel',
                    'title' => 'Introduction to Laravel',
                    'excerpt' => 'Learn the basics of Laravel framework',
                    'tags' => ['PHP', 'Laravel', 'Framework'],
                    'image' => null,
                    'date' => now()->subDays(10)->toIso8601String(),
                    'updatedAt' => now()->subDays(10)->toIso8601String(),
                ]),
            ],
        ];

        foreach ($documents as $doc) {
            $document = Document::create([
                'slug' => $doc['slug'],
                'category' => $doc['category'],
                'draft' => $doc['draft'],
                'frontmatter' => $doc['frontmatter'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Heading::create([
                'document_id' => $document->id,
                'text' => $doc['frontmatter']->title,
                'level' => 1,
                'section' => $doc['frontmatter']->title,
            ]);
        }
    }
}
