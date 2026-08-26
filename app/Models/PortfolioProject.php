<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'location',
        'category',
        'description',
        'long_description',
        'cover_image',
        'images',
        'details',
        'published',
        'sort_order',
    ];

    protected $casts = [
        'images' => 'array',
        'details' => 'array',
        'published' => 'boolean',
    ];
}
