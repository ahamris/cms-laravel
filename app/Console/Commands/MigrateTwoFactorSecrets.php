<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Laravel\Fortify\Fortify;

class MigrateTwoFactorSecrets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fa:migrate-secrets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing 2FA secrets to use Fortify encrypter';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $users = User::whereNotNull('two_factor_secret')->get();

        if ($users->isEmpty()) {
            $this->info('No users with 2FA found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$users->count()} user(s) with 2FA. Migrating secrets...");

        $migrated = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                // Try to decrypt with Fortify encrypter first
                try {
                    $secret = Fortify::currentEncrypter()->decrypt($user->two_factor_secret);
                    $this->line("User {$user->email}: Already using Fortify encrypter");
                    continue;
                } catch (\Exception $e) {
                    // If it fails, try with Laravel's default encrypt/decrypt
                    try {
                        $secret = decrypt($user->two_factor_secret);
                        
                        // Re-encrypt with Fortify encrypter
                        $user->two_factor_secret = Fortify::currentEncrypter()->encrypt($secret);
                        
                        // Also migrate recovery codes if they exist
                        if ($user->two_factor_recovery_codes) {
                            try {
                                $codes = json_decode(decrypt($user->two_factor_recovery_codes), true);
                                $user->two_factor_recovery_codes = Fortify::currentEncrypter()->encrypt(json_encode($codes));
                            } catch (\Exception $e) {
                                // Recovery codes might already be in Fortify format
                                try {
                                    $codes = json_decode(Fortify::currentEncrypter()->decrypt($user->two_factor_recovery_codes), true);
                                } catch (\Exception $e2) {
                                    $this->warn("User {$user->email}: Could not migrate recovery codes");
                                }
                            }
                        }
                        
                        $user->save();
                        $migrated++;
                        $this->info("User {$user->email}: Secret migrated successfully");
                    } catch (\Exception $e2) {
                        $failed++;
                        $this->error("User {$user->email}: Failed to migrate - {$e2->getMessage()}");
                    }
                }
            } catch (\Exception $e) {
                $failed++;
                $this->error("User {$user->email}: Error - {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("Migration complete!");
        $this->info("Migrated: {$migrated}");
        $this->info("Failed: {$failed}");

        return Command::SUCCESS;
    }
}
