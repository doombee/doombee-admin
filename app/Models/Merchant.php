<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Merchant extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];

    protected static function booted()
    {
        static::creating(function ($merchant) {
            $merchant->slug = Str::slug($merchant->name);
        });
    }
}
