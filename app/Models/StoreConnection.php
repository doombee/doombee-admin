<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Integrations\Enums\ConnectorType;

class StoreConnection extends Model
{
    protected $fillable = [
        'store_id',
        'name',
        'connector',
        'credentials',
        'settings',
        'enabled',
        'status',
        'last_connected_at',
        'last_sync_at',
        'last_error',
    ];

    protected function casts(): array
    {
        return [
            'connector' => ConnectorType::class,

            'credentials' => 'encrypted:array',

            'settings' => 'array',

            'enabled' => 'boolean',

            'last_connected_at' => 'datetime',

            'last_sync_at' => 'datetime',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
