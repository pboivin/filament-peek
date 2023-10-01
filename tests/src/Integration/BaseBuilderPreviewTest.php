<?php

namespace Pboivin\FilamentPeek\Tests\Integration;

use Pboivin\FilamentPeek\Tests\Models\Category;
use Pboivin\FilamentPeek\Tests\Models\Post;
use Pboivin\FilamentPeek\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('sees builder editor when creating a post', function () {
    actingAs(User::factory()->create());

    get('/admin/posts/create')
        ->assertSuccessful()
        ->assertSee('Test_Builder_Preview')
        ->assertSee('x-ref="builderEditor"', escape: false);
});

it('sees builder editor when editing a post', function () {
    actingAs(User::factory()->create());

    $post = Post::factory()
        ->for(Category::factory(), 'category')
        ->create();

    get('/admin/posts/'.$post->id.'/edit')
        ->assertSuccessful()
        ->assertSee('Test_Builder_Preview')
        ->assertSee('x-ref="builderEditor"', escape: false);
});
