<?php

namespace App\Models;

use Database\Factories\ActionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property array<string, mixed> $attributes
 * @property int $entity_id
 * @property string $entity_type
 */
class Action extends Model
{
    /** @use HasFactory<ActionFactory> */
    use HasFactory;

    protected $fillable = [
        'attributes',
        'entity_id',
        'entity_type',
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    /**
     * @return MorphTo<Model, $this>
     */
    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
