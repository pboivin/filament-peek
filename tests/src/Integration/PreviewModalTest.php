<?php

namespace Pboivin\FilamentPeek\Tests\Feature;

use Pboivin\FilamentPeek\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('sees preview modal assets by default', function () {
    actingAs(User::factory()->create());

    get('/admin')
        ->assertSuccessful()
        ->assertSee('filament-peek.css')
        ->assertSee('filament-peek.js');
});

it('does not see preview modal on dashboard', function () {
    actingAs(User::factory()->create());

    get('/admin')
        ->assertSuccessful()
        ->assertDontSee('filament-peek-modal-title');
});

it('sees preview modal on create page', function () {
    actingAs(User::factory()->create());

    get('/admin/posts/create')
        ->assertSuccessful()
        ->assertSee('filament-peek-modal-title');
});
