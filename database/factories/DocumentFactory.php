<?php

namespace BenBjurstrom\Prezet\Database\Factories;

use BenBjurstrom\Prezet\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model;
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    /**
     * @return array<string, array<string, array|bool|string>|bool|Carbon|string>
     */
    public function definition(): array
    {
        $createdAt = Carbon::now()->subDays(rand(1, 100));

        return [
            'slug' => $this->faker->unique()->slug,
            'category' => $this->faker->optional()->word,
            'draft' => $this->faker->boolean,
            'hash' => md5(random_bytes(16)),
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

    /**
     * Create a collection of models and persist them to the database.
     *
     * @param  (callable(array<string, mixed>): array<string, mixed>)|array<string, mixed>  $attributes
     * @return \Illuminate\Database\Eloquent\Collection<int, TModel>|TModel
     */
    public function create($attributes = [], ?Model $parent = null)
    {
        if (is_array($attributes)
            && isset($attributes['frontmatter'])
            && is_array($attributes['frontmatter'])) {
            $defaults = $this->definition()['frontmatter'];

            if (is_array($defaults)) {
                $attributes['frontmatter'] = array_merge($defaults, $attributes['frontmatter']);
            }
        }

        return parent::create($attributes, $parent);
    }
}
