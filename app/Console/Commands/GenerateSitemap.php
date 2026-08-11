<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap for the e-Kurva application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // Add main/static pages
        $this->addStaticPages($sitemap);

        // Add dynamic content pages
        $this->addDynamicPages($sitemap);

        // Save the sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully at public/sitemap.xml');

        return Command::SUCCESS;
    }

    /**
     * Add static pages to sitemap
     */
    private function addStaticPages(Sitemap $sitemap): void
    {
        // Homepage
        $sitemap->add(
            Url::create(route('home'))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(1.0)
        );

        // Authentication pages (public access)
        $sitemap->add(
            Url::create(url('/login'))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.7)
        );

        $sitemap->add(
            Url::create(url('/register'))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.7)
        );

        // Add any other static pages you want to include
        // Example: Privacy Policy, Terms of Service, etc.
        // Uncomment and modify as needed:
        /*
        $sitemap->add(
            Url::create(url('/privacy-policy'))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
                ->setPriority(0.5)
        );
        */
    }

    /**
     * Add dynamic content pages to sitemap
     */
    private function addDynamicPages(Sitemap $sitemap): void
    {
        // Add landing page sections if they have public URLs
        $this->addLandingPageContent($sitemap);

        // Add public services/layanans if they have individual pages
        $this->addLayananPages($sitemap);

        // Add help pages if they're publicly accessible
        $this->addHelpPages($sitemap);
    }

    /**
     * Add landing page content to sitemap
     */
    private function addLandingPageContent(Sitemap $sitemap): void
    {
        // If you have sections of your landing page with anchor links that are indexable
        $landingPageSections = [
            '#layanan' => 0.8,
            '#tentang' => 0.7,
            '#testimoni' => 0.6,
            '#paket' => 0.8,
            '#kontak' => 0.7,
        ];

        foreach ($landingPageSections as $section => $priority) {
            $sitemap->add(
                Url::create(route('home') . $section)
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority($priority)
            );
        }
    }

    /**
     * Add layanan/service pages to sitemap
     */
    private function addLayananPages(Sitemap $sitemap): void
    {
        try {
            $layanans = DB::table('lp_layanans')->get();

            foreach ($layanans as $layanan) {
                // If you have individual pages for each service
                // Uncomment and modify the URL structure as needed:
                /*
                $sitemap->add(
                    Url::create(url("/layanan/{$layanan->id}"))
                        ->setLastModificationDate(Carbon::parse($layanan->updated_at ?? $layanan->created_at))
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.7)
                );
                */
            }
        } catch (\Exception $e) {
            $this->warn("Could not add layanan pages: " . $e->getMessage());
        }
    }

    /**
     * Add help pages to sitemap
     */
    private function addHelpPages(Sitemap $sitemap): void
    {
        try {
            $helps = DB::table('lp_helps')->get();

            foreach ($helps as $help) {
                // If you have individual public help pages
                // Uncomment and modify the URL structure as needed:
                /*
                $sitemap->add(
                    Url::create(url("/help/{$help->id}"))
                        ->setLastModificationDate(Carbon::parse($help->updated_at ?? $help->created_at))
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.6)
                );
                */
            }
        } catch (\Exception $e) {
            $this->warn("Could not add help pages: " . $e->getMessage());
        }
    }
}
