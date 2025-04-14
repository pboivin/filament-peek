<?php

namespace Pboivin\FilamentPeek\Tests\Integration;

use Pboivin\FilamentPeek\Tests\Models\Category;
use Pboivin\FilamentPeek\Tests\Models\Post;

use function Pest\Laravel\get;

it('sees builder editor when creating a post', function () {
    $this->login();

    get('/admin/posts/create')
        ->assertSuccessful()
        ->assertSee('Test_Builder_Preview')
        ->assertSee('x-ref="builderEditor"', escape: false);
});

it('sees builder editor when editing a post', function () {
    $this->login();

    $post = Post::factory()
        ->for(Category::factory(), 'category')
        ->create();

    get('/admin/posts/'.$post->id.'/edit')
        ->assertSuccessful()
        ->assertSee('Test_Builder_Preview')
        ->assertSee('x-ref="builderEditor"', escape: false);
});
