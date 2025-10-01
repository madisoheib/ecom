<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\ThemeSettings;

class ResetDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset database: drop all tables, run migrations, seeders, and preserve current theme as default';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('This will drop all database tables. Are you sure?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->info('Starting database reset...');

        // Capture current active theme before dropping tables
        $currentTheme = null;
        try {
            $currentTheme = ThemeSettings::where('is_active', true)->first();
            if ($currentTheme) {
                $this->info("Found current active theme with primary color: {$currentTheme->primary_color}");
            }
        } catch (\Exception $e) {
            $this->warn('Could not capture current theme, will use seeder default');
        }

        // Drop all tables and run fresh migrations with seeders
        $this->info('Running fresh migrations with seeders...');
        $this->call('migrate:fresh', ['--seed' => true, '--force' => true]);

        // Set current theme as active (default)
        $this->info('Setting default theme...');
        try {
            ThemeSettings::query()->update(['is_active' => false]);
            
            if ($currentTheme) {
                // Find matching theme by primary color or create new one
                $matchingTheme = ThemeSettings::where('primary_color', $currentTheme->primary_color)->first();
                
                if ($matchingTheme) {
                    $matchingTheme->update(['is_active' => true]);
                    $this->info("Restored previous theme with primary color: {$matchingTheme->primary_color}");
                } else {
                    // Create the previous theme if it doesn't exist in seeders
                    $newTheme = ThemeSettings::create([
                        'primary_color' => $currentTheme->primary_color,
                        'secondary_color' => $currentTheme->secondary_color,
                        'accent_color' => $currentTheme->accent_color,
                        'background_color' => $currentTheme->background_color,
                        'text_color' => $currentTheme->text_color,
                        'name' => $currentTheme->name ?? 'Custom Theme',
                        'is_active' => true,
                    ]);
                    $this->info("Created and activated previous theme: {$newTheme->primary_color}");
                }
            } else {
                // Default to first theme if no current theme was found
                $firstTheme = ThemeSettings::first();
                if ($firstTheme) {
                    $firstTheme->update(['is_active' => true]);
                    $this->info("Set default theme with primary color: {$firstTheme->primary_color}");
                }
            }
        } catch (\Exception $e) {
            $this->error("Could not set default theme: " . $e->getMessage());
        }

        $this->info('Database reset completed successfully!');
        $this->info('Current theme has been preserved and set as default.');
    }
}
