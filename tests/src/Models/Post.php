<?php

namespace Pboivin\FilamentPeek\Tests\Models;

use Carbon\Carbon;
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

    public function scopePublished($query)
    {
        return $query
            ->whereNotNull('published_at')
            ->whereDate('published_at', '<=', Carbon::now());
    }

    public function scopeFeatured($query)
    {
        return $query
            ->published()
            ->where('is_featured', true);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getMainImage()
    {
        if ($this->main_image_upload) {
            return '/storage/'.$this->main_image_upload;
        }

        return $this->main_image_url;
    }

    protected static function newFactory()
    {
        return PostFactory::new();
    }
}
