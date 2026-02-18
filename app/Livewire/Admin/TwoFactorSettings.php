<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Laravel\Fortify\Fortify;
use PragmaRX\Google2FAQRCode\Google2FA;

class TwoFactorSettings extends Component
{
    public string $password = '';
    public string $code = '';
    public bool $twoFactorEnabled = false;
    public array $recoveryCodes = [];
    public ?array $newRecoveryCodes = null;
    public string $secret = '';
    public string $qrCode = '';

    public function mount(): void
    {
        $this->loadTwoFactorData();
    }

    protected function loadTwoFactorData(): void
    {
        $user = auth()->user();
        $google2fa = new Google2FA();

        // Generate secret if not exists
        if (!$user->two_factor_secret) {
            $secret = $google2fa->generateSecretKey();
            $user->update(['two_factor_secret' => Fortify::currentEncrypter()->encrypt($secret)]);
            $user->refresh();
        } else {
            $secret = Fortify::currentEncrypter()->decrypt($user->two_factor_secret);
        }

        $this->secret = $secret;

        // Generate QR code
        $this->qrCode = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        $this->twoFactorEnabled = !is_null($user->two_factor_confirmed_at);
        $this->recoveryCodes = $user->two_factor_recovery_codes 
            ? json_decode(Fortify::currentEncrypter()->decrypt($user->two_factor_recovery_codes), true) 
            : [];
    }

    public function enableTwoFactor(): void
    {
        $this->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();
        $google2fa = new Google2FA();

        if (!$user->two_factor_secret) {
            session()->flash('error', 'Two-factor authentication secret not found. Please refresh the page.');
            return;
        }

        $secret = Fortify::currentEncrypter()->decrypt($user->two_factor_secret);

        // Verify the code
        $valid = $google2fa->verifyKey($secret, $this->code);

        if (!$valid) {
            $this->addError('code', 'The provided two-factor authentication code was invalid.');
            return;
        }

        // Generate recovery codes
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = str()->random(10);
        }

        // Enable 2FA
        $user->update([
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => Fortify::currentEncrypter()->encrypt(json_encode($recoveryCodes)),
        ]);

        $this->twoFactorEnabled = true;
        $this->recoveryCodes = $recoveryCodes;
        $this->newRecoveryCodes = $recoveryCodes;
        $this->code = '';

        session()->flash('success', 'Two-factor authentication has been enabled.');
    }

    public function disableTwoFactor(): void
    {
        $this->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();

        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        $this->twoFactorEnabled = false;
        $this->recoveryCodes = [];
        $this->newRecoveryCodes = null;
        $this->password = '';

        // Reload data to generate new secret
        $this->loadTwoFactorData();

        session()->flash('success', 'Two-factor authentication has been disabled.');
    }

    public function regenerateRecoveryCodes(): void
    {
        $this->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();

        if (!$user->two_factor_confirmed_at) {
            session()->flash('error', 'Two-factor authentication is not enabled.');
            return;
        }

        // Generate new recovery codes
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = str()->random(10);
        }

        $user->update([
            'two_factor_recovery_codes' => Fortify::currentEncrypter()->encrypt(json_encode($recoveryCodes)),
        ]);

        $this->recoveryCodes = $recoveryCodes;
        $this->newRecoveryCodes = $recoveryCodes;
        $this->password = '';

        session()->flash('success', 'Recovery codes have been regenerated.');
    }

    public function render()
    {
        return view('livewire.admin.two-factor-settings');
    }
}
