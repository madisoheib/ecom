<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Homepage Slider';
    protected static ?string $modelLabel = 'Slider';
    protected static ?string $pluralModelLabel = 'Sliders';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Slide Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('subtitle')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),

                        SpatieMediaLibraryFileUpload::make('slider_image')
                            ->collection('slider_image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '21:9',
                            ])
                            ->maxFiles(1)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Button & Link')
                    ->schema([
                        Forms\Components\TextInput::make('button_text')
                            ->maxLength(100)
                            ->placeholder('Shop Now'),

                        Forms\Components\TextInput::make('button_url')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Styling')
                    ->schema([
                        Forms\Components\ColorPicker::make('background_color')
                            ->default('#ffffff'),

                        Forms\Components\ColorPicker::make('text_color')
                            ->default('#000000'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('slider_image')
                    ->collection('slider_image')
                    ->conversion('thumb')
                    ->height(60),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('subtitle')
                    ->searchable()
                    ->limit(40)
                    ->color('gray'),

                Tables\Columns\TextColumn::make('button_text')
                    ->label('Button')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->badge()
                    ->color('secondary'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All slides')
                    ->trueLabel('Active slides')
                    ->falseLabel('Inactive slides'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}