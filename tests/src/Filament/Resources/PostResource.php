<?php

namespace Pboivin\FilamentPeek\Tests\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;
use Pboivin\FilamentPeek\Forms\Components\PreviewLink;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages;
use Pboivin\FilamentPeek\Tests\Models\Post;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Site';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->columns(2)->schema([
                    Forms\Components\TextInput::make('title')
                        ->columnSpan(1)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            $set('slug', Str::slug($state));
                        }),
                    Forms\Components\TextInput::make('slug')
                        ->columnSpan(1)
                        ->required(),
                    Forms\Components\DateTimePicker::make('published_at')
                        ->columnSpan(1),
                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name')
                        ->columnSpan(1)
                        ->required(),
                    Forms\Components\Toggle::make('is_featured')
                        ->columnSpanFull()
                        ->required(),
                    Forms\Components\RichEditor::make('content')
                        ->columnSpanFull()
                        ->required(),
                    Forms\Components\TextInput::make('main_image_url')
                        ->label('Main image URL')
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('main_image_upload')
                        ->label('Main image upload')
                        ->columnSpanFull(),
                ]),

                PreviewLink::make(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable(),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable(),
                Tables\Columns\IconColumn::make('is_featured')->boolean()->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
