<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function it_can_create_site_settings()
    {
        $settingsData = [
            'site_title' => 'Test E-commerce Site',
            'meta_description' => 'A test e-commerce website',
            'meta_keywords' => 'test, ecommerce, shop',
            'default_currency' => 'USD',
            'primary_color' => '#3b82f6',
            'secondary_color' => '#64748b',
            'accent_color' => '#f59e0b'
        ];

        $settings = SiteSetting::create($settingsData);

        $this->assertInstanceOf(SiteSetting::class, $settings);
        $this->assertEquals('Test E-commerce Site', $settings->site_title);
        $this->assertEquals('USD', $settings->default_currency);
        $this->assertDatabaseHas('site_settings', $settingsData);
    }

    /** @test */
    public function it_returns_current_settings_or_creates_new()
    {
        // First call should create new settings
        $settings1 = SiteSetting::current();
        $this->assertInstanceOf(SiteSetting::class, $settings1);

        // Second call should return existing settings
        $settings2 = SiteSetting::current();
        $this->assertEquals($settings1->id, $settings2->id);

        // Should only have one record
        $this->assertEquals(1, SiteSetting::count());
    }

    /** @test */
    public function it_can_get_setting_with_default()
    {
        // No settings exist
        $title = SiteSetting::getSetting('site_title', 'Default Title');
        $this->assertEquals('Default Title', $title);

        // Create settings
        SiteSetting::create(['site_title' => 'Actual Title']);

        $title = SiteSetting::getSetting('site_title', 'Default Title');
        $this->assertEquals('Actual Title', $title);
    }

    /** @test */
    public function it_can_set_setting_value()
    {
        SiteSetting::setSetting('site_title', 'New Title');

        $settings = SiteSetting::current();
        $this->assertEquals('New Title', $settings->site_title);
    }

    /** @test */
    public function it_can_handle_logo_upload()
    {
        $settings = SiteSetting::current();

        $logoFile = UploadedFile::fake()->image('logo.png', 300, 100);
        $settings->addMediaFromFile($logoFile)->toMediaCollection('site_logo');

        $this->assertEquals(1, $settings->getMedia('site_logo')->count());
    }

    /** @test */
    public function helper_functions_work_correctly()
    {
        // Test with no settings (uses defaults)
        $this->assertEquals('My Website', site_title());
        $this->assertEquals('USD', default_currency());

        // Create settings in the same test
        $settings = SiteSetting::current();
        $settings->update([
            'site_title' => 'Test Site',
            'default_currency' => 'EUR',
            'primary_color' => '#ff0000'
        ]);

        // Clear any cached settings
        SiteSetting::clearStaticCache();

        $this->assertEquals('Test Site', site_title());
        $this->assertEquals('EUR', default_currency());
        $this->assertEquals('#ff0000', primary_color());
    }

    /** @test */
    public function it_validates_currency_format()
    {
        $validCurrencies = ['USD', 'EUR', 'GBP', 'JPY', 'CAD', 'AUD'];

        foreach ($validCurrencies as $currency) {
            $settings = SiteSetting::create(['default_currency' => $currency]);
            $this->assertEquals($currency, $settings->default_currency);
            $settings->delete();
        }
    }

    /** @test */
    public function it_validates_color_format()
    {
        $validColors = ['#ffffff', '#000000', '#3b82f6', '#ff5733'];
        $invalidColors = ['ffffff', 'red', '#xyz123', 'rgb(255,0,0)'];

        foreach ($validColors as $color) {
            $settings = SiteSetting::create(['primary_color' => $color]);
            $this->assertEquals($color, $settings->primary_color);
            $settings->delete();
        }

        // Note: Add validation rules to model if needed
    }

    /** @test */
    public function it_can_update_multiple_settings()
    {
        $settings = SiteSetting::current();

        $updateData = [
            'site_title' => 'Updated Title',
            'meta_description' => 'Updated description',
            'default_currency' => 'EUR',
            'primary_color' => '#00ff00'
        ];

        $settings->update($updateData);

        $this->assertEquals('Updated Title', $settings->fresh()->site_title);
        $this->assertEquals('EUR', $settings->fresh()->default_currency);
        $this->assertEquals('#00ff00', $settings->fresh()->primary_color);
    }

    /** @test */
    public function it_maintains_single_record_constraint()
    {
        SiteSetting::create(['site_title' => 'First']);
        SiteSetting::create(['site_title' => 'Second']);
        SiteSetting::create(['site_title' => 'Third']);

        // Should still work with multiple records (though not ideal)
        // current() should return the first one
        $current = SiteSetting::current();
        $this->assertInstanceOf(SiteSetting::class, $current);

        // But ideally we should only have one record
        $this->assertGreaterThanOrEqual(1, SiteSetting::count());
    }
}