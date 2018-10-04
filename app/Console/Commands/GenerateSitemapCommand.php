<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a command for sitemap rendering';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $isDebug = config('app.debug');
        $pages = \App\Page::all();
        foreach (\App\Region::all() as $region) {
            /** @var \Roumen\Sitemap\Sitemap $sitemap */
            $sitemap = \Illuminate\Support\Facades\App::make('sitemap');

            if (!empty($region->subdomain)) {
                $isMoscow = $region->subdomain !== "moscow";
                $host = "http" . (!$isDebug ? "s" : "") . "://" . (!$isMoscow ? $region->subdomain . "." : "")
                    . config('app.domain');
                foreach ($pages as $page) {
                    $pageUrl = $host . (empty($page->slug) ? $page->slug : "/{$page->slug}");
                    $modTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $page->updated_at)
                        ->format('Y-m-d\TH:i:sP');
                    $isHomePage = $page->id === 1;
                    $sitemap->add(
                        $pageUrl,
                        $modTime,
                        $isHomePage ? 1.0 : 0.6,
                        $isHomePage ? 'daily' : 'monthly'
                    );
                }

                $sitemap->store("xml", "sitemap" . (!$isMoscow ? "-{$region->subdomain}" : ""));
            }
        }
    }
}
