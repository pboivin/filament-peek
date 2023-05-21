<?php

use function Pest\Laravel\get;

use Pboivin\FilamentPeek\Tests\Models\Post;
use Pboivin\FilamentPeek\Tests\Models\User;

it('can login', function () {
    $this->actingAs(User::factory()->create());

    $response = get('/admin');

    $response->assertSuccessful();

    $response->assertSee('Dashboard');
});

it('can visit resource page', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $response = get('/admin/posts/'.$post->id.'/edit');

    $response->assertSuccessful();

    $response->assertSee('Edit Post');
});

it('sees preview modal', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $response = get('/admin/posts/'.$post->id.'/edit');

    $response->assertSuccessful();

    $response->assertSee('filament-peek-preview-modal');
});

it('sees device presets', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $response = get('/admin/posts/'.$post->id.'/edit');

    $response->assertSuccessful();

    foreach (config('filament-peek.devicePresets') as $name => $preset) {
        $response->assertSee($name);
        $response->assertSee($preset['icon']);
    }
});
