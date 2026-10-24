<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'store_id',
        'external_id',
        'provider',
        'sku',
        'name',
        'description',
        'active',
        'price',
        'currency',
        'stock',
        'ean',
        'manufacturer',
        'categories',
        'category_ids',
        'images',
        'attributes',
    ];


    protected $casts = [
        'active' => 'boolean',
        'categories' => 'array',
        'category_ids' => 'array',
        'images' => 'array',
        'attributes' => 'array',
    ];
}
