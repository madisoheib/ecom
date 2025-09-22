<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Filament\Resources\SiteSettingResource\RelationManagers;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Settings')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Unique identifier for this setting'),

                        Forms\Components\Textarea::make('value')
                            ->nullable()
                            ->rows(3)
                            ->helperText('The setting value'),

                        Forms\Components\Textarea::make('description')
                            ->nullable()
                            ->rows(2)
                            ->helperText('Optional description for this setting'),
                    ]),

                Forms\Components\Section::make('Color Settings')
                    ->schema([
                        Forms\Components\ColorPicker::make('value')
                            ->visible(fn ($get) => in_array($get('key'), [
                                'primary_color',
                                'secondary_color',
                                'accent_color'
                            ]))
                            ->helperText('Choose a color for this theme setting'),
                    ])
                    ->visible(fn ($get) => in_array($get('key'), [
                        'primary_color',
                        'secondary_color',
                        'accent_color'
                    ])),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('value')
                    ->limit(50)
                    ->wrap()
                    ->formatStateUsing(function ($state, $record) {
                        if (in_array($record->key, ['primary_color', 'secondary_color', 'accent_color'])) {
                            return view('filament.color-preview', ['color' => $state]);
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->limit(30)
                    ->wrap(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'color' => 'Color Settings',
                        'text' => 'Text Settings',
                        'general' => 'General Settings',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $value): Builder => match ($value) {
                                'color' => $query->whereIn('key', ['primary_color', 'secondary_color', 'accent_color']),
                                'text' => $query->whereIn('key', ['site_title', 'site_description', 'meta_description', 'meta_keywords']),
                                'general' => $query->whereNotIn('key', ['primary_color', 'secondary_color', 'accent_color', 'site_title', 'site_description', 'meta_description', 'meta_keywords']),
                            }
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('key');
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
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
