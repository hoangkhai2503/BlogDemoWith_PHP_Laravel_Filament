<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->reactive()
                                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                                        $set('slug', Str::slug($state));
                                    })
                                    ->required()
                                    ->maxLength(2048),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(2048),
                            ]),

                        Forms\Components\RichEditor::make('body')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('meta_title'),
                        Forms\Components\TextInput::make('meta_description'),
                        Forms\Components\Toggle::make('active')
                            ->required(),
                        Forms\Components\Grid::make(2)
                            ->schema([

                                Forms\Components\DateTimePicker::make('published_at'),
                                Forms\Components\Select::make('categories')
                                    ->multiple()
                                    ->relationship('categories', 'title')
                                    ->required(),
                            ]),

                        Forms\Components\FileUpload::make('thumbnail'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('title')->searchable(['title', 'body'])->sortable(),
            // Tables\Columns\TextColumn::make('slug')
            //     ->searchable(),
            Tables\Columns\ImageColumn::make('thumbnail')
            ->url(fn ($record) => asset('storage/' . $record->thumbnail)),
        
            Tables\Columns\IconColumn::make('active')->sortable()->boolean(),
            Tables\Columns\TextColumn::make('published_at')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('user.name')
                ->numeric()
                ->sortable()
                ->sortable(),
            // Tables\Columns\TextColumn::make('created_at')
            //     ->dateTime()
            //     ->sortable()
            //     ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
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
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
