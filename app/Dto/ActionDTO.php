<?php

namespace App\Dto;

use Illuminate\Database\Eloquent\Model;

class ActionDTO
{
    public function __construct(
        public Model $entity,
        /** @var array<string, mixed> $attributes */
        public array $attributes,
    ) {}
}
