<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiAgent extends Model
{
    protected $fillable = [
        'name',
        'implementation',
        'enabled',
        'description_override',
        'documentation_override',
        'additional_keywords',
        'training_enabled',
    ];

    protected function casts(): array
    {
        return [
            'additional_keywords' => 'array',
            'enabled' => 'boolean',
            'training_enabled' => 'boolean',
        ];
    }
}
