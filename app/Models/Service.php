<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'eyebrow',
        'items',
        'description',
        'image',
        'published',
        'sort_order',
    ];

    protected $casts = [
        'items' => 'array',
        'published' => 'boolean',
    ];
}
