<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Pages\Page;

class ProductResource extends Resource
{
    use Translatable;

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Shop Management';
    protected static ?int $navigationSort = 1;

    public static function getTranslatableLocales(): array
    {
        return ['en', 'fr', 'ar'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Product::class, 'slug', ignoreRecord: true),

                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(Product::class, 'sku', ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Textarea::make('short_description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('specifications')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing & Inventory')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Base Price')
                            ->required()
                            ->numeric()
                            ->prefix('CAD $')
                            ->minValue(0)
                            ->helperText('Base price in Canadian Dollars. Country-specific pricing can be set below.'),

                        Forms\Components\TextInput::make('compare_price')
                            ->label('Compare Price')
                            ->numeric()
                            ->prefix('CAD $')
                            ->minValue(0)
                            ->helperText('Original price for discount display (in CAD)'),

                        Forms\Components\TextInput::make('registration_discount')
                            ->label('Registration Discount (%)')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(5)
                            ->helperText('Discount percentage for registered users'),

                        Forms\Components\TextInput::make('stock_quantity')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                    ])->columns(4),

                Forms\Components\Section::make('Organization')
                    ->schema([
                        Forms\Components\Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\Textarea::make('description'),
                            ]),

                        Forms\Components\Select::make('categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\Textarea::make('description'),
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Media')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('product_images')
                            ->collection('product_images')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->maxFiles(10),
                    ])->columnSpanFull(),

                Forms\Components\Section::make('Slug Translations')
                    ->schema([
                        Forms\Components\TextInput::make('slug_translations.en')
                            ->label('URL Slug (English)')
                            ->helperText('Leave empty to auto-generate from English name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug_translations.fr')
                            ->label('URL Slug (French)')
                            ->helperText('Leave empty to auto-generate from French name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug_translations.ar')
                            ->label('URL Slug (Arabic)')
                            ->helperText('Leave empty to auto-generate from Arabic name')
                            ->maxLength(255),
                    ])->columns(3)->collapsible(),

                Forms\Components\Section::make('SEO & Meta')
                    ->schema([
                        Forms\Components\Tabs::make('SEO Content')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Basic SEO')
                                    ->schema([
                                        Forms\Components\TextInput::make('focus_keyword')
                                            ->label('Focus Keyword')
                                            ->helperText('Main keyword you want to rank for')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('canonical_url')
                                            ->label('Canonical URL')
                                            ->helperText('Override default canonical URL')
                                            ->url(),
                                        Forms\Components\Toggle::make('index_follow')
                                            ->label('Index & Follow')
                                            ->helperText('Allow search engines to index and follow links')
                                            ->default(true),
                                        Forms\Components\TextInput::make('content_score')
                                            ->label('SEO Score')
                                            ->disabled()
                                            ->numeric()
                                            ->suffix('/100'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('French SEO')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title.fr')
                                            ->label('Meta Title (French)')
                                            ->helperText('Optimal length: 30-60 characters')
                                            ->maxLength(60),
                                        Forms\Components\Textarea::make('meta_description.fr')
                                            ->label('Meta Description (French)')
                                            ->helperText('Optimal length: 120-160 characters')
                                            ->maxLength(160)
                                            ->rows(3),
                                        Forms\Components\TagsInput::make('meta_keywords.fr')
                                            ->label('Meta Keywords (French)')
                                            ->helperText('Separate keywords with commas'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('English SEO')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title.en')
                                            ->label('Meta Title (English)')
                                            ->helperText('Optimal length: 30-60 characters')
                                            ->maxLength(60),
                                        Forms\Components\Textarea::make('meta_description.en')
                                            ->label('Meta Description (English)')
                                            ->helperText('Optimal length: 120-160 characters')
                                            ->maxLength(160)
                                            ->rows(3),
                                        Forms\Components\TagsInput::make('meta_keywords.en')
                                            ->label('Meta Keywords (English)')
                                            ->helperText('Separate keywords with commas'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Arabic SEO')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title.ar')
                                            ->label('Meta Title (Arabic)')
                                            ->helperText('Optimal length: 30-60 characters')
                                            ->maxLength(60),
                                        Forms\Components\Textarea::make('meta_description.ar')
                                            ->label('Meta Description (Arabic)')
                                            ->helperText('Optimal length: 120-160 characters')
                                            ->maxLength(160)
                                            ->rows(3),
                                        Forms\Components\TagsInput::make('meta_keywords.ar')
                                            ->label('Meta Keywords (Arabic)')
                                            ->helperText('Separate keywords with commas'),
                                    ]),
                            ])->columnSpanFull(),
                    ])->columnSpanFull(),

                Forms\Components\Section::make('Countries & Availability')
                    ->schema([
                        Forms\Components\Select::make('default_country')
                            ->label('Default Country')
                            ->options([
                                'CA' => 'Canada',
                                'US' => 'United States',
                                'FR' => 'France',
                                'AE' => 'United Arab Emirates',
                                'KW' => 'Kuwait',
                                'OM' => 'Oman',
                                'DZ' => 'Algeria',
                            ])
                            ->default('CA')
                            ->required(),

                        Forms\Components\Repeater::make('productCountries')
                            ->label('Country-Specific Settings')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('country_code')
                                    ->label('Country')
                                    ->options([
                                        'CA' => 'Canada',
                                        'US' => 'United States',
                                        'FR' => 'France',
                                        'AE' => 'United Arab Emirates',
                                        'KW' => 'Kuwait',
                                        'OM' => 'Oman',
                                        'DZ' => 'Algeria',
                                    ])
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $currencies = [
                                            'CA' => 'CAD',
                                            'US' => 'USD',
                                            'FR' => 'EUR',
                                            'AE' => 'AED',
                                            'KW' => 'KWD',
                                            'OM' => 'OMR',
                                            'DZ' => 'DZD',
                                        ];
                                        $set('currency', $currencies[$state] ?? 'USD');
                                    }),

                                Forms\Components\TextInput::make('price')
                                    ->label('Country Price')
                                    ->numeric()
                                    ->minValue(0)
                                    ->helperText('Leave empty to use default product price'),

                                Forms\Components\TextInput::make('currency')
                                    ->label('Currency')
                                    ->maxLength(3)
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->helperText('Auto-filled based on selected country'),

                                Forms\Components\TextInput::make('stock_quantity')
                                    ->label('Stock Quantity')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->required(),

                                Forms\Components\Toggle::make('is_available')
                                    ->label('Available in this country')
                                    ->default(true),
                            ])
                            ->columns(3)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                isset($state['country_code'])
                                    ? match($state['country_code']) {
                                        'CA' => 'Canada',
                                        'US' => 'United States',
                                        'FR' => 'France',
                                        'AE' => 'United Arab Emirates',
                                        'KW' => 'Kuwait',
                                        'OM' => 'Oman',
                                        'DZ' => 'Algeria',
                                        default => $state['country_code']
                                    }
                                    : null
                            ),
                    ])->columnSpanFull(),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),

                        Forms\Components\Toggle::make('is_featured')
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('product_images')
                    ->collection('product_images')
                    ->limit(1)
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->money(site_currency())
                    ->sortable(),

                Tables\Columns\TextColumn::make('registration_discount')
                    ->label('Reg. Discount')
                    ->suffix('%')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state == 0 => 'danger',
                        $state < 10 => 'warning',
                        default => 'success',
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('sales_count')
                    ->label('Sales')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('default_country')
                    ->label('Default Country')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'CA' => 'Canada',
                        'US' => 'United States',
                        'FR' => 'France',
                        'AE' => 'UAE',
                        'KW' => 'Kuwait',
                        'OM' => 'Oman',
                        'DZ' => 'Algeria',
                        default => $state
                    })
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('productCountries_count')
                    ->label('Countries')
                    ->counts('productCountries')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand', 'name'),

                Tables\Filters\SelectFilter::make('categories')
                    ->relationship('categories', 'name'),

                Tables\Filters\TernaryFilter::make('is_active'),

                Tables\Filters\TernaryFilter::make('is_featured'),

                Tables\Filters\Filter::make('out_of_stock')
                    ->query(fn (Builder $query): Builder => $query->where('stock_quantity', 0))
                    ->label('Out of Stock'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_active' => true])))
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-check'),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_active' => false])))
                        ->requiresConfirmation()
                        ->color('danger')
                        ->icon('heroicon-o-x-mark'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
