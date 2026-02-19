<?php

namespace Database\Seeders;

use App\Models\TailwindPlus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TailwindPlusSeeder extends Seeder
{
    /**
     * Number of records to process before committing a batch
     */
    protected int $batchSize = 50;

    /**
     * Delay in milliseconds between each record insertion
     */
    protected int $delayMs = 10;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (TailwindPlus::count() > 0) {
            $this->command->info('TailwindPlus components already imported. Skipping...');
            return;
        }

        // Increase memory limit for large JSON file processing
        $originalMemoryLimit = ini_get('memory_limit');
        ini_set('memory_limit', '2G');

        $this->command->info('Starting TailwindPlus component import...');
        $this->command->info("Batch size: {$this->batchSize} records, Delay: {$this->delayMs}ms per record");

        $jsonPath = database_path('tailwind_plus_export.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("JSON file not found at: {$jsonPath}");
            ini_set('memory_limit', $originalMemoryLimit);
            return;
        }

        $this->command->info('Reading JSON file...');

        // Read JSON file
        $jsonContent = File::get($jsonPath);
        $data = json_decode($jsonContent, true);
        unset($jsonContent); // Free memory immediately after decoding

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Failed to parse JSON: ' . json_last_error_msg());
            return;
        }

        if (!is_array($data)) {
            $this->command->error('Invalid JSON structure: expected an array');
            return;
        }

        try {
            $totalImported = 0;
            $totalSkipped = 0;
            $totalUpdated = 0;
            $batchCount = 0;

            // Iterate through the exported data array
            foreach ($data as $record) {
                if (!is_array($record)) {
                    $totalSkipped++;
                    continue;
                }

                // Start transaction for batch if needed
                if ($batchCount === 0) {
                    DB::beginTransaction();
                }

                // Extract only the fillable fields and exclude timestamps and id
                $fillableData = [
                    'category' => $record['category'] ?? null,
                    'component_group' => $record['component_group'] ?? null,
                    'component_name' => $record['component_name'] ?? '',
                    'code' => $record['code'] ?? '',
                    'preview' => $record['preview'] ?? null,
                    'version' => $record['version'] ?? 1,
                    'is_active' => $record['is_active'] ?? true,
                ];

                // Use firstOrCreate to check if record exists based on unique combination
                // Check by component_name, category, and component_group
                $component = TailwindPlus::firstOrCreate(
                    [
                        'component_name' => $fillableData['component_name'],
                        'category' => $fillableData['category'],
                        'component_group' => $fillableData['component_group'],
                    ],
                    $fillableData
                );

                // If component was just created, increment imported count
                if ($component->wasRecentlyCreated) {
                    $totalImported++;
                } else {
                    // Update existing record if data has changed
                    $component->update($fillableData);
                    $totalUpdated++;
                }

                $batchCount++;

                // Commit batch and add delay
                if ($batchCount >= $this->batchSize) {
                    DB::commit();
                    $this->command->info("Processed {$totalImported} new records, {$totalUpdated} updated... (Skipped: {$totalSkipped})");
                    $batchCount = 0;

                    // Delay after batch commit
                    usleep($this->delayMs * 1000 * 2); // Longer delay after batch
                } else {
                    // Small delay between individual inserts
                    usleep($this->delayMs * 1000);
                }
            }

            // Commit any remaining records in the current batch
            if ($batchCount > 0) {
                DB::commit();
            }

            $this->command->info("Import completed successfully!");
            $this->command->info("Total new components imported: {$totalImported}");
            $this->command->info("Total existing components updated: {$totalUpdated}");
            $this->command->info("Total records skipped: {$totalSkipped}");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Import failed: {$e->getMessage()}");
            $this->command->error($e->getTraceAsString());
            throw $e;
        } finally {
            // Restore original memory limit
            ini_set('memory_limit', $originalMemoryLimit);
        }
    }

    /**
     * Clean code by decoding JSON escape sequences
     * Removes escaped characters like \n, \", \\, etc.
     */
    protected function cleanCode(?string $code): ?string
    {
        if ($code === null || $code === '') {
            return $code;
        }

        // Since json_decode should already handle escape sequences,
        // but if we still see literal \n, \", etc., clean them manually
        $cleaned = $code;

        // Replace escape sequences in order (most specific first)
        // Handle double backslashes first to avoid double-processing
        $cleaned = preg_replace('/\\\\\\\\/', '\\', $cleaned);

        // Replace common escape sequences
        $cleaned = str_replace('\\n', "\n", $cleaned);      // Newlines
        $cleaned = str_replace('\\r', "\r", $cleaned);      // Carriage returns
        $cleaned = str_replace('\\t', "\t", $cleaned);     // Tabs
        $cleaned = str_replace('\\"', '"', $cleaned);       // Escaped double quotes
        $cleaned = str_replace("\\'", "'", $cleaned);      // Escaped single quotes
        $cleaned = str_replace('\\`', '`', $cleaned);       // Escaped backticks

        return $cleaned;
    }
}
