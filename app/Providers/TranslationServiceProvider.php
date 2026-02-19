<?php

namespace App\Providers;

use App\Services\DatabaseTranslationLoader;
use App\Services\TranslationService;
use Illuminate\Translation\TranslationServiceProvider as BaseTranslationServiceProvider;

class TranslationServiceProvider extends BaseTranslationServiceProvider
{
    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            $translationService = $app->make(TranslationService::class);
            return new DatabaseTranslationLoader($translationService);
        });
    }
}
