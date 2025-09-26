<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThemeSettingsResource\Pages;
use App\Filament\Resources\ThemeSettingsResource\RelationManagers;
use App\Models\ThemeSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ColorColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThemeSettingsResource extends Resource
{
    protected static ?string $model = ThemeSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Theme Settings';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Theme Colors')
                    ->description('Customize the appearance of your website')
                    ->schema([
                        TextInput::make('name')
                            ->label('Theme Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Blue Corporate, Dark Mode'),
                        
                        ColorPicker::make('primary_color')
                            ->label('Primary Color')
                            ->required()
                            ->hex(),
                        ColorPicker::make('secondary_color')
                            ->label('Secondary Color')
                            ->required()
                            ->hex(),
                        ColorPicker::make('accent_color')
                            ->label('Accent Color')
                            ->required()
                            ->hex(),
                        ColorPicker::make('background_color')
                            ->label('Background Color')
                            ->required()
                            ->hex(),
                        ColorPicker::make('text_color')
                            ->label('Text Color')
                            ->required()
                            ->hex(),
                        Toggle::make('is_active')
                            ->label('Active Theme')
                            ->default(false)
                            ->helperText('Only one theme can be active at a time'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Theme Name')
                    ->searchable()
                    ->sortable(),
                    
                ColorColumn::make('primary_color')
                    ->label('Primary'),
                ColorColumn::make('secondary_color')
                    ->label('Secondary'),
                ColorColumn::make('accent_color')
                    ->label('Accent'),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state) {
                            $record->activate();
                        }
                    }),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check')
                    ->action(function ($record) {
                        $record->activate();
                    })
                    ->visible(fn ($record) => !$record->is_active),
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
            'index' => Pages\ListThemeSettings::route('/'),
            'create' => Pages\CreateThemeSettings::route('/create'),
            'edit' => Pages\EditThemeSettings::route('/{record}/edit'),
        ];
    }
}
