<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Filament\Resources\TagResource\RelationManagers;
use App\Models\Tag;
use Faker\Core\Color;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;


class TagResource extends Resource
{
    protected static ?string $model =   Tag::class;
    protected static ?string $slug = 'tag';
    protected static ?string $navigationIcon = 'heroicon-m-squares-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxValue(255)
                    ->columnSpanFull(),

                ColorPicker::make('color')
                    ->required(),

                Select::make('language')
                    ->options([
                        'en' => 'English',
                        'bn' => 'Bangla',
                    ])
                    ->default('en')
                    ->id('language')
                    ->required(),

                Hidden::make('created_by')
                    ->default(auth()->user()->id)
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                ColorColumn::make('color')
                    ->label('Color'),

                TextColumn::make('language')
                    ->getStateUsing(fn (Tag $record): string => $record->language == 'en' ? 'en' : 'bn')
                    ->badge()
                    ->color(fn (Tag $record): string => $record->language == 'en' ? 'success' : 'warning'),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->sortable()
                    ->date(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('language')
                    ->options([
                        'bn' => 'Bangla',
                        'en' => 'English',
                    ])
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->paginated([25]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTags::route('/'),
        ];
    }
}
