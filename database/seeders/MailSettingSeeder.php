<?php

namespace Database\Seeders;

use App\Models\MailSetting;
use Illuminate\Database\Seeder;

class MailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (MailSetting::count() === 0) {
            MailSetting::create([
                'mail_mailer' => 'log',
                'mail_host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
                'mail_port' => env('MAIL_PORT', '2525'),
                'mail_username' => env('MAIL_USERNAME'),
                'mail_password' => env('MAIL_PASSWORD'),
                'mail_encryption' => env('MAIL_ENCRYPTION', 'tls'),
                'mail_from_address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
                'mail_from_name' => env('MAIL_FROM_NAME', 'Open Publicatie'),
            ]);
        }
    }
}
