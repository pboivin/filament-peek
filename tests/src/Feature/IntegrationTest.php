<?php

namespace Pboivin\FilamentPeek\Tests\Feature;

use Pboivin\FilamentPeek\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('can access dashboard', function () {
    actingAs(User::factory()->create());

    get('/admin')
        ->assertSuccessful()
        ->assertSee('Dashboard');
});

it('does not see preview modal on dashboard', function () {
    actingAs(User::factory()->create());

    get('/admin')
        ->assertDontSee('filament-peek-modal-title');
});

it('sees preview modal assets by default', function () {
    actingAs(User::factory()->create());

    get('/admin')
        ->assertSee('filament-peek.css')
        ->assertSee('filament-peek.js');
});
