<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiTool extends Model
{
    protected $fillable = [
        'name',
        'agent',
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

    public function examples(): HasMany
    {
        return $this->hasMany(AiToolExample::class);
    }
}
