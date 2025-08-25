<?php

namespace Pboivin\FilamentPeek\Tests\Feature;

use Livewire\Livewire;
use Pboivin\FilamentPeek\Tests\App\Filament\Resources\PageResource\Pages\EditPage;
use Pboivin\FilamentPeek\Tests\App\Filament\Resources\PostResource\Pages\EditPost;
use Pboivin\FilamentPeek\Tests\App\Models\Category;
use Pboivin\FilamentPeek\Tests\App\Models\Page;
use Pboivin\FilamentPeek\Tests\App\Models\Post;

it('dispatches open preview modal browser event', function () {
    $this->login();

    $page = Page::factory()->create(['title' => 'Test Page']);

    Livewire::test(EditPage::class, ['record' => $page->id])
        ->assertSeeHtml('Test Page')
        ->call('openPreviewModal')
        ->assertDispatched('open-preview-modal');
});

it('dispatches open preview tab browser event', function () {
    $this->login();

    $page = Page::factory()->create(['title' => 'Test Page']);

    Livewire::test(EditPage::class, ['record' => $page->id])
        ->assertSeeHtml('Test Page')
        ->call('openPreviewTab')
        ->assertDispatched('open-preview-tab');
});

it('dispatches close preview modal browser event', function () {
    $this->login();

    $page = Page::factory()->create(['title' => 'Test Page']);

    Livewire::test(EditPage::class, ['record' => $page->id])
        ->assertSeeHtml('Test Page')
        ->call('closePreviewModal')
        ->assertDispatched('close-preview-modal');
});

it('dispatches open builder editor browser event', function () {
    $this->login();

    $post = Post::factory()
        ->for(Category::factory(), 'category')
        ->create();

    Livewire::test(EditPost::class, ['record' => $post->id])
        ->call('openPreviewModalForBuilder', 'blocks')
        ->assertDispatched('openBuilderEditor');
});
