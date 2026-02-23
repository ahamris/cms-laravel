<?php

namespace App\Services;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Support\Arr;

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
     * Merges file-based translations (PHP in lang path) with database translations.
     * Database entries take precedence; file-based strings fill in missing keys.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string|null  $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null): array
    {
        $fileLines = $this->loadFromFile($locale, $group);
        $dbLines = $this->translationService->getAllForLocale($locale, $group);

        // DB returns flat keys (e.g. 'image_optimizer.optimize_images'); Laravel expects nested or dot-notation.
        // Start with file (nested), then overlay each DB key into a nested structure.
        $merged = $fileLines;

        foreach ($dbLines as $key => $value) {
            Arr::set($merged, $key, $value);
        }

        return $merged;
    }

    /**
     * Load translations from PHP lang files (resources/lang or lang_path()) as fallback.
     */
    protected function loadFromFile(string $locale, string $group): array
    {
        $paths = [
            resource_path("lang/{$locale}/{$group}.php"),
            lang_path("{$locale}/{$group}.php"),
        ];

        foreach ($paths as $path) {
            if (is_file($path)) {
                $lines = require $path;

                return is_array($lines) ? $lines : [];
            }
        }

        return [];
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
