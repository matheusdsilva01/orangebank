<?php

namespace App\Models;

use Database\Factories\GoalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    /** @use HasFactory<GoalFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'attributes',
        'threshold',
    ];

    protected function casts(): array
    {
        return [
            'attributes' => 'array',
        ];
    }
}
