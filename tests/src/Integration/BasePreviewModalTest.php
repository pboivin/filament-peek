<?php

namespace Pboivin\FilamentPeek\Tests\Integration;

use Pboivin\FilamentPeek\Tests\Models\Page;
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
        ->assertDontSee('x-ref="previewModalBody"', escape: false);
});

it('sees preview modal when creating a page', function () {
    actingAs(User::factory()->create());

    get('/admin/pages/create')
        ->assertSuccessful()
        ->assertSee('Test_Preview_Action')
        ->assertSee('x-ref="previewModalBody"', escape: false)
        ->assertDontSee('x-ref="builderEditor"', escape: false);
});

it('sees preview modal when editing a page', function () {
    actingAs(User::factory()->create());

    $page = Page::factory()->create();

    get('/admin/pages/'.$page->id.'/edit')
        ->assertSuccessful()
        ->assertSee('Test_Preview_Action')
        ->assertSee('x-ref="previewModalBody"', escape: false)
        ->assertDontSee('x-ref="builderEditor"', escape: false);
});
