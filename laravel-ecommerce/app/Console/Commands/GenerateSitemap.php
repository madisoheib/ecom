<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SitemapService;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--multilingual : Generate multilingual sitemaps}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML sitemap for the website';

    /**
     * Execute the console command.
     */
    public function handle(SitemapService $sitemapService)
    {
        $this->info('Generating sitemap...');

        if ($this->option('multilingual')) {
            $sitemapService->generateMultilingual();
            $this->info('Multilingual sitemaps generated successfully!');
        } else {
            $sitemapService->generate();
            $this->info('Sitemap generated successfully!');
        }

        $this->info('Sitemap available at: ' . url('/sitemap.xml'));

        return Command::SUCCESS;
    }
}
