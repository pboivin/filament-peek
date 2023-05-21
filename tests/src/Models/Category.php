<?php

namespace Pboivin\FilamentPeek\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pboivin\FilamentPeek\Tests\Database\Factories\CategoryFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
    ];

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
