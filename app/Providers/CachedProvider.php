<?php

namespace App\Providers;

use App\Models\ExternalCode;
use App\Models\FooterLink;
use App\Models\MailSetting;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class CachedProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        if (! app()->runningInConsole()) {

            if (Schema::hasTable('footer_links')) {
                View::share('footerLinks', FooterLink::getCached());
            }

            if (Schema::hasTable('settings')) {
                View::share('settings', Setting::getCached());
            }

            if (Schema::hasTable('external_codes')) {
                View::share('externalCodes', ExternalCode::getCached());
            }

        }

        // Set mail configuration from database (console check first avoids DB during artisan)
        if (! app()->runningInConsole() && Schema::hasTable('mail_settings')) {
            try {
                $mailSettings = MailSetting::getSettings();
                if ($mailSettings && $mailSettings->mail_mailer && $mailSettings->mail_host) {
                    config([
                        'mail.default' => $mailSettings->mail_mailer,
                        'mail.mailer' => $mailSettings->mail_mailer,
                        'mail.from.address' => $mailSettings->mail_from_address ?: config('mail.from.address'),
                        'mail.from.name' => $mailSettings->mail_from_name ?: config('mail.from.name'),
                        'mail.mailers.smtp' => [
                            'transport' => 'smtp',
                            'host' => $mailSettings->mail_host,
                            'port' => $mailSettings->mail_port ?: 587,
                            'encryption' => $mailSettings->mail_encryption,
                            'username' => $mailSettings->mail_username,
                            'password' => $mailSettings->mail_password,
                            'timeout' => null,
                            'local_domain' => null,
                        ],
                    ]);
                }
            } catch (\Exception $e) {
                // Silently fail if mail settings table doesn't exist or has issues
                // This prevents breaking the application during migrations
            }
        }

    }
}
