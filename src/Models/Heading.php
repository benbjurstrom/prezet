<?php

namespace BenBjurstrom\Prezet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $level
 * @property int $section
 * @property int $document_id
 * @property string $text
 */
class Heading extends Model
{
    protected $connection = 'prezet';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'section' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Document, Heading>
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
