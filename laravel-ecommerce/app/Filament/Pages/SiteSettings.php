<?php

namespace App\Filament\Pages;

use App\Models\WebsiteSetting;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Actions\Action;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Settings (Old)';
    protected static ?string $title = 'Site Settings (Old)';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 2;
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_title' => WebsiteSetting::get('site_title', 'My Website'),
            'site_logo' => WebsiteSetting::get('site_logo'),
            'meta_description' => WebsiteSetting::get('meta_description'),
            'meta_keywords' => WebsiteSetting::get('meta_keywords'),
            'default_currency' => WebsiteSetting::get('default_currency', 'USD'),
            'primary_color' => WebsiteSetting::get('primary_color', '#3b82f6'),
            'secondary_color' => WebsiteSetting::get('secondary_color', '#64748b'),
            'accent_color' => WebsiteSetting::get('accent_color', '#10b981'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
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

        foreach ($data as $key => $value) {
            if ($value !== null) {
                WebsiteSetting::set($key, $value);
            }
        }

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }
}
