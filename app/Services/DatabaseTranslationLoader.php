<?php

namespace App\Services;

use Illuminate\Contracts\Translation\Loader;

class DatabaseTranslationLoader implements Loader
{
    /**
     * @var TranslationService
     */
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Load the messages for the given locale.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string|null  $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null): array
    {
        // For this implementation, we ignore namespaces and load everything from the database.
        // The TranslationService will handle caching.
        return $this->translationService->getAllForLocale($locale, $group);
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param  string  $namespace
     * @param  string  $hint
     * @return void
     */
    public function addNamespace($namespace, $hint): void
    {
        // Not needed for our database loader.
    }

    /**
     * Add a new JSON path to the loader.
     *
     * @param  string  $path
     * @return void
     */
    public function addJsonPath($path): void
    {
        // Not needed for our database loader.
    }

    /**
     * Get an array of all the registered namespaces.
     *
     * @return array
     */
    public function namespaces(): array
    {
        return [];
    }
}
