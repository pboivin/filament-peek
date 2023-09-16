<?php

namespace Pboivin\FilamentPeek\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Pboivin\FilamentPeek\Tests\Models\Post;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'slug' => Str::slug($this->faker->sentence(4)),
            'title' => $this->faker->sentence(4),
            'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',
        ];
    }
}
