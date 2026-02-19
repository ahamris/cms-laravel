<?php

namespace App\Console\Commands;

use App\Services\TranslationService;
use Illuminate\Console\Command;

class ClearTranslationsCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:clear-cache {--locale= : The locale to clear the cache for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the translations cache from the database and Redis/file.';

    /**
     * @var TranslationService
     */
    protected $translationService;

    /**
     * Create a new command instance.
     *
     * @param TranslationService $translationService
     */
    public function __construct(TranslationService $translationService)
    {
        parent::__construct();
        $this->translationService = $translationService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $locale = $this->option('locale');

        try {
            $this->translationService->clearCache($locale);

            $message = $locale
                ? "Translation cache cleared for locale '{$locale}' successfully!"
                : 'All translation caches cleared successfully!';

            $this->info($message);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to clear translation cache: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
