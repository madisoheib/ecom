<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageSiteSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::first();

        if ($settings) {
            $this->form->fill($settings->toArray());
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->action('save')
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                Forms\Components\TextInput::make('site_title')
                                    ->label('Site Title')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Textarea::make('site_description')
                                    ->label('Site Description')
                                    ->rows(2),

                                Forms\Components\FileUpload::make('site_logo')
                                    ->label('Site Logo')
                                    ->image()
                                    ->directory('logos')
                                    ->preserveFilenames(),

                                Forms\Components\TextInput::make('default_currency')
                                    ->label('Default Currency')
                                    ->default('USD')
                                    ->maxLength(3),
                            ]),

                        Forms\Components\Tabs\Tab::make('Colors')
                            ->schema([
                                Forms\Components\Section::make('Theme Colors')
                                    ->description('Customize the main colors of your website')
                                    ->schema([
                                        Forms\Components\ColorPicker::make('primary_color')
                                            ->label('Primary Color')
                                            ->default('#3b82f6')
                                            ->helperText('Main brand color used throughout the site'),

                                        Forms\Components\ColorPicker::make('secondary_color')
                                            ->label('Secondary Color')
                                            ->default('#64748b')
                                            ->helperText('Secondary color for accents and highlights'),

                                        Forms\Components\ColorPicker::make('accent_color')
                                            ->label('Accent Color')
                                            ->default('#10b981')
                                            ->helperText('Color for call-to-action buttons and important elements'),
                                    ])
                                    ->columns(3),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(160),

                                Forms\Components\Textarea::make('meta_keywords')
                                    ->label('Meta Keywords')
                                    ->rows(2)
                                    ->helperText('Comma-separated keywords'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Contact')
                            ->schema([
                                Forms\Components\TextInput::make('company_email')
                                    ->label('Company Email')
                                    ->email(),

                                Forms\Components\TextInput::make('company_phone')
                                    ->label('Company Phone')
                                    ->tel(),

                                Forms\Components\Textarea::make('company_address')
                                    ->label('Company Address')
                                    ->rows(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Social Media')
                            ->schema([
                                Forms\Components\TextInput::make('facebook_url')
                                    ->label('Facebook URL')
                                    ->url()
                                    ->prefix('https://'),

                                Forms\Components\TextInput::make('twitter_url')
                                    ->label('Twitter/X URL')
                                    ->url()
                                    ->prefix('https://'),

                                Forms\Components\TextInput::make('instagram_url')
                                    ->label('Instagram URL')
                                    ->url()
                                    ->prefix('https://'),

                                Forms\Components\TextInput::make('linkedin_url')
                                    ->label('LinkedIn URL')
                                    ->url()
                                    ->prefix('https://'),

                                Forms\Components\TextInput::make('youtube_url')
                                    ->label('YouTube URL')
                                    ->url()
                                    ->prefix('https://'),
                            ])
                            ->columns(1),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settings = SiteSetting::first();

        if ($settings) {
            $settings->update($data);
        } else {
            SiteSetting::create($data);
        }

        // Clear the cached settings
        SiteSetting::clearStaticCache();

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}