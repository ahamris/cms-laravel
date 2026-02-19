<?php

namespace App\Console\Commands;

use App\Models\Translation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportTranslationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:import 
                            {--locale= : Import translations for specific locale only}
                            {--group= : Import translations for specific group only}
                            {--force : Overwrite existing translations}
                            {--dry-run : Show what would be imported without actually importing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import translations from language files into the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $specificLocale = $this->option('locale');
        $specificGroup = $this->option('group');
        $force = $this->option('force');

        $this->info($isDryRun ? 'Starting translation import (DRY RUN)...' : 'Starting translation import...');

        $locales = $specificLocale ? [$specificLocale] : config('app.available_locales', ['en', 'nl']);
        $importedCount = 0;
        $skippedCount = 0;

        foreach ($locales as $locale) {
            $localePath = lang_path($locale);
            
            if (!File::isDirectory($localePath)) {
                $this->warn("Locale directory not found: {$localePath}");
                continue;
            }

            // Get all PHP files in the locale directory
            $files = File::glob("{$localePath}/*.php");
            
            foreach ($files as $filePath) {
                $group = pathinfo($filePath, PATHINFO_FILENAME);
                
                // Skip if specific group is requested and this isn't it
                if ($specificGroup && $group !== $specificGroup) {
                    continue;
                }
                
                if (File::exists($filePath)) {
                    $translations = include $filePath;
                    
                    if (is_array($translations)) {
                        if ($isDryRun) {
                            $count = $this->countTranslations($translations);
                            $this->info("[DRY RUN] Would import {$count} translations for locale '{$locale}' and group '{$group}'.");
                            $importedCount += $count;
                        } else {
                            $result = $this->importTranslations($translations, $locale, $group, '', $force);
                            $importedCount += $result['imported'];
                            $skippedCount += $result['skipped'];
                            
                            $message = "Imported {$result['imported']} translations for locale '{$locale}' and group '{$group}'.";
                            if ($result['skipped'] > 0) {
                                $message .= " Skipped {$result['skipped']} existing translations.";
                            }
                            $this->info($message);
                        }
                    } else {
                        $this->warn("Invalid translation file format: {$filePath}");
                    }
                } else {
                    $this->warn("File not found: {$filePath}");
                }
            }
        }

        if ($isDryRun) {
            $this->info("DRY RUN completed! Would import {$importedCount} translations.");
        } else {
            $message = "Translation import completed successfully! Total imported: {$importedCount}";
            if ($skippedCount > 0) {
                $message .= ", skipped: {$skippedCount}";
            }
            $this->info($message);
            
            $this->call('translations:clear-cache');
        }

        return 0;
    }

    /**
     * Recursively import translations.
     */
    protected function importTranslations(array $translations, string $locale, string $group, string $prefix = '', bool $force = false): array
    {
        $imported = 0;
        $skipped = 0;
        
        foreach ($translations as $key => $value) {
            $currentKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $result = $this->importTranslations($value, $locale, $group, $currentKey, $force);
                $imported += $result['imported'];
                $skipped += $result['skipped'];
            } else {
                // Check if translation already exists
                $existing = Translation::getTranslation($currentKey, $locale, $group);
                
                if ($existing && !$force) {
                    // $this->line("  - Skipped existing key: {$group}.{$currentKey}");
                    $skipped++;
                } else {
                    Translation::setTranslation($currentKey, $locale, $value, $group);
                    $this->line("  - " . ($existing ? 'Updated' : 'Imported') . " key: {$group}.{$currentKey}");
                    $imported++;
                }
            }
        }
        
        return ['imported' => $imported, 'skipped' => $skipped];
    }

    /**
     * Count translations recursively for dry run.
     */
    protected function countTranslations(array $translations): int
    {
        $count = 0;
        
        foreach ($translations as $value) {
            if (is_array($value)) {
                $count += $this->countTranslations($value);
            } else {
                $count++;
            }
        }
        
        return $count;
    }
}
