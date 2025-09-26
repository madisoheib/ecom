<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Slider Information')
                    ->schema([
                        Forms\Components\KeyValue::make('title')
                            ->label('Title (Multilingual)')
                            ->keyLabel('Language')
                            ->valueLabel('Title')
                            ->required()
                            ->default([
                                'en' => '',
                                'fr' => '',
                                'ar' => ''
                            ]),

                        Forms\Components\KeyValue::make('subtitle')
                            ->label('Subtitle (Multilingual)')
                            ->keyLabel('Language')
                            ->valueLabel('Subtitle')
                            ->default([
                                'en' => '',
                                'fr' => '',
                                'ar' => ''
                            ]),

                        Forms\Components\KeyValue::make('description')
                            ->label('Description (Multilingual)')
                            ->keyLabel('Language')
                            ->valueLabel('Description')
                            ->default([
                                'en' => '',
                                'fr' => '',
                                'ar' => ''
                            ]),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Visual Settings')
                    ->schema([
                        Forms\Components\TextInput::make('image_path')
                            ->label('Image URL')
                            ->url()
                            ->required()
                            ->placeholder('https://example.com/image.jpg'),

                        Forms\Components\ColorPicker::make('background_color')
                            ->label('Background Color')
                            ->default('#000000'),

                        Forms\Components\ColorPicker::make('text_color')
                            ->label('Text Color')
                            ->default('#ffffff'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Button Settings')
                    ->schema([
                        Forms\Components\TextInput::make('button_text')
                            ->label('Button Text')
                            ->placeholder('Shop Now'),

                        Forms\Components\TextInput::make('button_url')
                            ->label('Button URL')
                            ->url()
                            ->placeholder('/products'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->square()
                    ->size(60),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->getStateUsing(fn (Slider $record): string => 
                        $record->getTranslation('title', 'en') ?: 
                        $record->getTranslation('title', 'fr') ?: 
                        'No title'
                    )
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('button_text')
                    ->label('Button')
                    ->searchable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order');
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
