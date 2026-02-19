<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class ProfileController extends AdminBaseController
{
    /**
     * Display the user's profile page.
     */
    public function index()
    {
        $user = Auth::user();

        return view('admin.profile.index', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'type' => ['nullable', 'string', 'max:255'],
            'telefoon' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'bedrijfsnaam' => ['nullable', 'string', 'max:255'],
            'kvk_nummer' => ['nullable', 'string', 'max:255'],
            'btw_nummer' => ['nullable', 'string', 'max:255'],
            'iban' => ['nullable', 'string', 'max:255'],
            'adres' => ['nullable', 'string', 'max:255'],
            'postcode' => ['nullable', 'string', 'max:255'],
            'plaats' => ['nullable', 'string', 'max:255'],
            'land' => ['nullable', 'string', 'max:255'],
        ]);

        $user->fill($validated);
        $user->save();

        return redirect()->route('admin.profile.index')->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.index')->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Setup two-factor authentication for the user (generate secret and QR code).
     */
    public function setupTwoFactor(Request $request)
    {
        $user = $request->user();
        $google2fa = new Google2FA();

        // Generate a secret key
        $secret = $google2fa->generateSecretKey();
        
        // Generate QR code
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        // Generate QR code SVG
        $qrCode = $this->generateQrCodeSvg($qrCodeUrl);

        // Store secret temporarily (not confirmed yet)
        $user->update([
            'two_factor_secret' => encrypt($secret),
        ]);

        return redirect()->route('admin.profile.index')
            ->with('status', '2fa-setup')
            ->with('qrCode', $qrCode)
            ->with('secret', $secret);
    }

    /**
     * Confirm and enable two-factor authentication after user verification.
     */
    public function confirmTwoFactor(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();
        $google2fa = new Google2FA();

        // Get the secret
        $secret = decrypt($user->two_factor_secret);

        // Verify the code
        $valid = $google2fa->verifyKey($secret, $request->code);

        if (!$valid) {
            return redirect()->route('admin.profile.index')
                ->withErrors(['code' => 'The provided two-factor authentication code was invalid.']);
        }

        // Generate recovery codes
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = Str::random(10);
        }

        // Confirm 2FA
        $user->update([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            'two_factor_confirmed_at' => now(),
        ]);

        return redirect()->route('admin.profile.index')
            ->with('status', '2fa-enabled')
            ->with('recoveryCodes', $recoveryCodes);
    }

    /**
     * Cancel two-factor authentication setup.
     */
    public function cancelTwoFactorSetup(Request $request)
    {
        $user = $request->user();

        // Only clear the secret if 2FA is not confirmed yet
        if (!$user->two_factor_confirmed_at) {
            $user->update([
                'two_factor_secret' => null,
            ]);
        }

        return redirect()->route('admin.profile.index')
            ->with('status', '2fa-setup-cancelled');
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();

        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        return redirect()->route('admin.profile.index')
            ->with('status', '2fa-disabled');
    }

    /**
     * Generate new recovery codes.
     */
    public function generateRecoveryCodes(Request $request)
    {
        $user = $request->user();

        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = Str::random(10);
        }

        $user->update([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        ]);

        return redirect()->route('admin.profile.index')
            ->with('recoveryCodes', $recoveryCodes);
    }

    /**
     * Generate QR code SVG for 2FA setup
     */
    private function generateQrCodeSvg($qrCodeUrl)
    {
        return QrCode::size(200)->generate($qrCodeUrl);
    }
}
