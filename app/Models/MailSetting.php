<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperMailSetting
 */
class MailSetting extends BaseModel
{
    const string CACHE_KEY = 'mail_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget(self::CACHE_KEY);
        });
    }

    /**
     * Get the current mail settings.
     */
    public static function getSettings()
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return self::firstOrCreate([], [
                'mail_mailer' => config('mail.default', 'smtp'),
                'mail_host' => config('mail.mailers.smtp.host', 'localhost'),
                'mail_port' => config('mail.mailers.smtp.port', 587),
                'mail_username' => config('mail.mailers.smtp.username', ''),
                'mail_encryption' => config('mail.mailers.smtp.encryption', 'tls'),
                'mail_from_address' => config('mail.from.address', 'hello@example.com'),
                'mail_from_name' => config('mail.from.name', 'Laravel'),
            ]);
        });
    }

    /**
     * Update the mail configuration with the current settings for testing.
     */
    public static function updateMailConfigForTesting()
    {
        $settings = self::getSettings();

        if ($settings && $settings->mail_mailer && $settings->mail_host) {
            config([
                'mail.default' => $settings->mail_mailer,
                'mail.mailer' => $settings->mail_mailer,
                'mail.from.address' => $settings->mail_from_address ?: config('mail.from.address'),
                'mail.from.name' => $settings->mail_from_name ?: config('mail.from.name'),
                'mail.mailers.smtp' => [
                    'transport' => 'smtp',
                    'host' => $settings->mail_host,
                    'port' => $settings->mail_port ?: 587,
                    'encryption' => $settings->mail_encryption,
                    'username' => $settings->mail_username,
                    'password' => $settings->mail_password,
                    'timeout' => null,
                    'local_domain' => null,
                ],
            ]);
        } else {
            throw new \Exception('Mail settings not configured properly. Please configure mail settings first.');
        }
    }
}
