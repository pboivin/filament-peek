<?php

namespace Pboivin\FilamentPeek\Tests\Integration;

use function Pest\Laravel\get;

it('does not see preview modal assets when disabled', function () {
    $this->login();

    get('/admin')
        ->assertSuccessful()
        ->assertDontSee('filament-peek.css')
        ->assertDontSee('filament-peek.js');
});
