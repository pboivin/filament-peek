<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources;

use Filament\Forms\Components\Actions;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Pboivin\FilamentPeek\Forms\Actions\InlinePreviewAction;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages;
use Pboivin\FilamentPeek\Tests\Models\Page;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Actions::make([
                InlinePreviewAction::make()
                    ->label('Preview Changes')
                    ->previewModalData(fn () => ['initial_data' => 'InlinePreviewAction']),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
            ])
            ->actions([
                ListPreviewAction::make()
                    ->previewModalData(fn () => ['initial_data' => 'ListPreviewAction']),
            ])
            ->filters([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
