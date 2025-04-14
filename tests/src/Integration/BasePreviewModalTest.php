<?php

namespace Pboivin\FilamentPeek\Tests\Integration;

use Pboivin\FilamentPeek\Tests\Models\Page;

use function Pest\Laravel\get;

it('sees preview modal assets by default', function () {
    $this->login();

    get('/admin')
        ->assertSuccessful()
        ->assertSee('filament-peek.css')
        ->assertSee('filament-peek.js');
});

it('does not see preview modal on dashboard', function () {
    $this->login();

    get('/admin')
        ->assertSuccessful()
        ->assertDontSee('x-ref="previewModalBody"', escape: false);
});

it('sees preview modal when creating a page', function () {
    $this->login();

    get('/admin/pages/create')
        ->assertSuccessful()
        ->assertSee('Test_Preview_Action')
        ->assertSee('x-ref="previewModalBody"', escape: false)
        ->assertDontSee('x-ref="builderEditor"', escape: false);
});

it('sees preview modal when editing a page', function () {
    $this->login();

    $page = Page::factory()->create();

    get('/admin/pages/'.$page->id.'/edit')
        ->assertSuccessful()
        ->assertSee('Test_Preview_Action')
        ->assertSee('x-ref="previewModalBody"', escape: false)
        ->assertDontSee('x-ref="builderEditor"', escape: false);
});
