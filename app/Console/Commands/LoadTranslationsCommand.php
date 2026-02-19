<?php

namespace App\Console\Commands;

use App\Services\TranslationService;
use Illuminate\Console\Command;

class LoadTranslationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'translations:load-cache {--locale= : Specific locale to load}';

    /**
     * The console command description.
     */
    protected $description = 'Load translations from database to cache (Redis/File)';

    /**
     * Execute the console command.
     */
    public function handle(TranslationService $translationService)
    {
        $locale = $this->option('locale');
        
        $cacheDriver = config('translation.cache_driver', 'file');
        $this->info("Loading translations to {$cacheDriver} cache...");
        
        try {
            $translationService->loadToCache($locale);
            
            if ($locale) {
                $this->info("Translations for locale '{$locale}' loaded to {$cacheDriver} cache successfully!");
            } else {
                $this->info("All translations loaded to {$cacheDriver} cache successfully!");
            }
            
        } catch (\Exception $e) {
            $this->error('Failed to load translations to cache: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
