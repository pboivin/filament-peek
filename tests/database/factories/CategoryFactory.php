<?php

namespace Pboivin\FilamentPeek\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Pboivin\FilamentPeek\Tests\Models\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'slug' => Str::slug($this->faker->sentence(2)),
            'name' => $this->faker->sentence(2),
        ];
    }
}
