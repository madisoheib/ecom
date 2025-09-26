<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnalyticsSettingsResource\Pages;
use App\Filament\Resources\AnalyticsSettingsResource\RelationManagers;
use App\Models\AnalyticsSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnalyticsSettingsResource extends Resource
{
    protected static ?string $model = AnalyticsSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Analytics Settings';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Google Analytics')
                    ->description('Configure Google Analytics tracking')
                    ->schema([
                        TextInput::make('google_analytics_id')
                            ->label('Google Analytics ID')
                            ->placeholder('G-XXXXXXXXXX')
                            ->helperText('Your Google Analytics 4 measurement ID'),
                        TextInput::make('google_tag_manager_id')
                            ->label('Google Tag Manager ID')
                            ->placeholder('GTM-XXXXXXX')
                            ->helperText('Your Google Tag Manager container ID'),
                    ])
                    ->columns(2),
                    
                Section::make('Facebook Pixel')
                    ->description('Configure Facebook Pixel tracking')
                    ->schema([
                        TextInput::make('facebook_pixel_id')
                            ->label('Facebook Pixel ID')
                            ->placeholder('123456789012345')
                            ->helperText('Your Facebook Pixel ID'),
                    ]),
                    
                Section::make('Privacy & Tracking Settings')
                    ->description('Configure privacy and tracking options')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Enable Analytics')
                                    ->default(false)
                                    ->helperText('Enable or disable all analytics tracking'),
                                Toggle::make('track_ecommerce')
                                    ->label('Track E-commerce')
                                    ->default(true)
                                    ->helperText('Track purchase and cart events'),
                                Toggle::make('anonymize_ip')
                                    ->label('Anonymize IP')
                                    ->default(true)
                                    ->helperText('Anonymize visitor IP addresses for GDPR compliance'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('google_analytics_id')
                    ->label('GA ID')
                    ->placeholder('Not set')
                    ->limit(20),
                TextColumn::make('google_tag_manager_id')
                    ->label('GTM ID')
                    ->placeholder('Not set')
                    ->limit(15),
                TextColumn::make('facebook_pixel_id')
                    ->label('FB Pixel')
                    ->placeholder('Not set')
                    ->limit(15),
                IconColumn::make('track_ecommerce')
                    ->label('E-commerce')
                    ->boolean(),
                IconColumn::make('anonymize_ip')
                    ->label('Anonymize IP')
                    ->boolean(),
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
            'index' => Pages\ListAnalyticsSettings::route('/'),
            'create' => Pages\CreateAnalyticsSettings::route('/create'),
            'edit' => Pages\EditAnalyticsSettings::route('/{record}/edit'),
        ];
    }
}
