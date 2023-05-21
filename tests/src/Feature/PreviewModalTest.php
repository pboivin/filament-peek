<?php

use Pboivin\FilamentPeek\Tests\Models\User;

it('starts here', function () {
    $this->actingAs(User::factory()->create());

});
