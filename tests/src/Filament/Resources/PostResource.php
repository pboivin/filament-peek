<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources;

use Filament\Forms\Components\Actions;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Pboivin\FilamentPeek\Forms\Actions\InlinePreviewAction;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages;
use Pboivin\FilamentPeek\Tests\Models\Post;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Actions::make([
                InlinePreviewAction::make()
                    ->label('Test_Builder_Preview')
                    ->builderPreview('content_blocks'),
            ])
                ->columnSpanFull()
                ->alignRight(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([])
            ->actions([])
            ->filters([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
