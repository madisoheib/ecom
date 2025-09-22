<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Actions\Action;

class GeneralSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $navigationLabel = 'General Settings';
    protected static ?string $title = 'General Settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.general-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::current();
        $this->form->fill($settings->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Website Information')
                    ->schema([
                        TextInput::make('site_title')
                            ->label('Site Title')
                            ->required()
                            ->maxLength(255),

                        FileUpload::make('site_logo')
                            ->label('Site Logo')
                            ->image()
                            ->directory('logos')
                            ->visibility('public'),

                        Textarea::make('site_description')
                            ->label('Site Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Brief description of your website'),

                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Recommended length: 120-160 characters'),

                        Textarea::make('meta_keywords')
                            ->label('Meta Keywords')
                            ->rows(2)
                            ->helperText('Separate keywords with commas'),

                        Select::make('default_currency')
                            ->label('Default Currency')
                            ->options([
                                'USD' => 'US Dollar (USD)',
                                'EUR' => 'Euro (EUR)',
                                'GBP' => 'British Pound (GBP)',
                                'CAD' => 'Canadian Dollar (CAD)',
                                'AUD' => 'Australian Dollar (AUD)',
                                'JPY' => 'Japanese Yen (JPY)',
                                'CNY' => 'Chinese Yuan (CNY)',
                                'INR' => 'Indian Rupee (INR)',
                            ])
                            ->required()
                            ->searchable(),
                    ])
                    ->columns(2),

                Section::make('Contact Information')
                    ->schema([
                        TextInput::make('company_email')
                            ->label('Company Email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('company_phone')
                            ->label('Company Phone')
                            ->tel()
                            ->maxLength(255),

                        Textarea::make('company_address')
                            ->label('Company Address')
                            ->rows(3)
                            ->maxLength(500),
                    ])
                    ->columns(2),

                Section::make('Social Media Links')
                    ->schema([
                        TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/yourpage'),

                        TextInput::make('twitter_url')
                            ->label('Twitter URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://twitter.com/yourhandle'),

                        TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/yourhandle'),

                        TextInput::make('linkedin_url')
                            ->label('LinkedIn URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://linkedin.com/company/yourcompany'),

                        TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://youtube.com/c/yourchannel'),
                    ])
                    ->columns(2),

                Section::make('Theme Colors')
                    ->schema([
                        ColorPicker::make('primary_color')
                            ->label('Primary Color')
                            ->required(),

                        ColorPicker::make('secondary_color')
                            ->label('Secondary Color')
                            ->required(),

                        ColorPicker::make('accent_color')
                            ->label('Accent Color')
                            ->required(),
                    ])
                    ->columns(3),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settings = SiteSetting::current();
        $settings->update($data);

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }
}
