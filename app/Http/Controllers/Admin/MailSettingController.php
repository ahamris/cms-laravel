<?php

namespace App\Http\Controllers\Admin;

use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailSettingController extends AdminBaseController
{
    /**
     * Display the mail settings form.
     */
    public function index()
    {
        $settings = MailSetting::getSettings();

        return view('admin.mail-settings.index', compact('settings'));
    }

    /**
     * Update the mail settings.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_mailer' => 'required|string|in:smtp,sendmail,mailgun,ses,log',
            'mail_host' => 'required_if:mail_mailer,smtp|string|max:255',
            'mail_port' => 'required_if:mail_mailer,smtp|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|in:tls,ssl',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $settings = MailSetting::getSettings();
        $settings->update($request->only([
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
        ]));

        return redirect()->route('admin.settings.mail.index')
            ->with('success', 'Mail settings updated successfully.');
    }

    /**
     * Test mail configuration by sending a test email.
     */
    public function testMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            $settings = MailSetting::getSettings();

            if ($settings->mail_mailer === 'log') {
                // For log mailer, just simulate sending
                config(['mail.default' => 'log']);
                Mail::raw('This is a test email from your mail configuration.', function ($message) use ($request) {
                    $message->to($request->test_email)
                        ->subject('Test Email - Mail Configuration');
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Test email logged successfully. Check storage/logs/laravel.log for the email content.',
                ]);
            } else {
                // Update mail config with current settings for testing
                MailSetting::updateMailConfigForTesting();

                // Use the configured mailer
                config(['mail.default' => $settings->mail_mailer]);

                // Send test email
                Mail::raw('This is a test email from your mail configuration.', function ($message) use ($request) {
                    $message->to($request->test_email)
                        ->subject('Test Email - Mail Configuration');
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Test email sent successfully to '.$request->test_email,
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: '.$e->getMessage(),
            ], 422);
        }
    }
}
