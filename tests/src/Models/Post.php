<?php

namespace Pboivin\FilamentPeek\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pboivin\FilamentPeek\Tests\Database\Factories\PostFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'is_featured',
        'main_image_url',
        'main_image_upload',
        'category_id',
        'published_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return PostFactory::new();
    }
}
