<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiToolExample extends Model
{
    protected $fillable = [
        'ai_tool_id',
        'user_message',
        'arguments',
        'expected_action',
        'notes',
        'training_enabled',
    ];

    protected function casts(): array
    {
        return [
            'arguments' => 'array',
            'training_enabled' => 'boolean',
        ];
    }

    public function tool(): BelongsTo
    {
        return $this->belongsTo(
            AiTool::class,
            'ai_tool_id'
        );
    }
}
