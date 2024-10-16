<?php

namespace Database\Factories\BenBjurstrom\Prezet\Models;

use BenBjurstrom\Prezet\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    /**
     * @return array<string, string>
     */
    public function definition(): array
    {
        $createdAt = Carbon::now()->subDays(rand(1, 100));

        return [
            'slug' => $this->faker->unique()->slug,
            'category' => $this->faker->optional()->word,
            'draft' => $this->faker->boolean,
            'frontmatter' => [
                'title' => $this->faker->sentence,
                'excerpt' => $this->faker->paragraph,
                'category' => $this->faker->optional()->word,
                'tags' => $this->faker->words(3),
                'image' => $this->faker->optional()->imageUrl(),
                'draft' => $this->faker->boolean,
                'date' => $createdAt->toIso8601String(),
            ],
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
