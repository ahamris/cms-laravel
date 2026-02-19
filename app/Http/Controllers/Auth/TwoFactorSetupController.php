<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Str;

class TwoFactorSetupController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show 2FA setup page with QR code.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $google2fa = new Google2FA();

        // Generate secret if not exists
        if (!$user->two_factor_secret) {
            $secret = $google2fa->generateSecretKey();
            $user->update(['two_factor_secret' => encrypt($secret)]);
        } else {
            $secret = decrypt($user->two_factor_secret);
        }

        // Generate QR code URL
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return response()->json([
            'secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
            'is_enabled' => !is_null($user->two_factor_confirmed_at)
        ]);
    }

    /**
     * Enable 2FA after verifying the code.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = $request->user();
        $google2fa = new Google2FA();

        if (!$user->two_factor_secret) {
            return response()->json(['message' => '2FA not set up'], 422);
        }

        $secret = decrypt($user->two_factor_secret);
        $valid = $google2fa->verifyKey($secret, $request->code);

        if (!$valid) {
            return response()->json(['message' => 'Invalid verification code'], 422);
        }

        // Generate recovery codes
        $recoveryCodes = collect(range(1, 8))->map(function () {
            return Str::random(10);
        })->toArray();

        $user->update([
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes))
        ]);

        return response()->json([
            'message' => '2FA enabled successfully',
            'recovery_codes' => $recoveryCodes
        ]);
    }

    /**
     * Disable 2FA.
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);

        $user = $request->user();
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null
        ]);

        return response()->json(['message' => '2FA disabled successfully']);
    }

    /**
     * Generate new recovery codes.
     */
    public function generateRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);

        $user = $request->user();

        if (!$user->two_factor_confirmed_at) {
            return response()->json(['message' => '2FA is not enabled'], 422);
        }

        $recoveryCodes = collect(range(1, 8))->map(function () {
            return Str::random(10);
        })->toArray();

        $user->update([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes))
        ]);

        return response()->json([
            'message' => 'Recovery codes generated',
            'recovery_codes' => $recoveryCodes
        ]);
    }
}
