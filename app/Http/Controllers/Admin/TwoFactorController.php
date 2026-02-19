<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFactorController extends AdminBaseController
{
    protected $google2fa;

    public function __construct()
    {
        parent::__construct();
        $this->google2fa = new Google2FA();
    }

    /**
     * Show the two-factor authentication settings page.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        return view('admin.security.two-factor.index', [
            'user' => $user,
            'hasTwoFactor' => $user->hasTwoFactorEnabled(),
            'recoveryCodes' => $user->hasTwoFactorEnabled() ? $user->getRecoveryCodesAttribute() : []
        ]);
    }

    /**
     * Enable two-factor authentication.
     */
    public function enable(Request $request): JsonResponse
    {
        $user = auth()->user();

        if ($user->hasTwoFactorEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Two-factor authentication is already enabled.'
            ], 400);
        }

        // Generate a new secret
        $secret = $this->google2fa->generateSecretKey();
        
        // Store temporarily in session for confirmation
        session(['2fa_temp_secret' => $secret]);

        // Generate QR code URL for Google Authenticator
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        // Generate QR code as SVG (ensure string for JSON response)
        $qrCodeSvg = (string) QrCode::format('svg')->size(200)->generate($qrCodeUrl);

        return response()->json([
            'success' => true,
            'secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
            'qr_code_svg' => $qrCodeSvg,
            'message' => 'Scan the QR code with your authenticator app and enter the verification code to enable 2FA.'
        ]);
    }

    /**
     * Confirm and activate two-factor authentication.
     */
    public function confirm(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string']
        ]);

        $user = auth()->user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided password is incorrect.'
            ], 400);
        }

        // Get the temporary secret from session
        $secret = session('2fa_temp_secret');
        if (!$secret) {
            return response()->json([
                'success' => false,
                'message' => 'No pending 2FA setup found. Please start the setup process again.'
            ], 400);
        }

        // Verify the code
        $valid = $this->google2fa->verifyKey($secret, $request->code);
        if (!$valid) {
            return response()->json([
                'success' => false,
                'message' => 'The provided verification code is invalid.'
            ], 400);
        }

        // Enable 2FA for the user
        $user->enableTwoFactor($secret);

        // Clear the temporary secret
        session()->forget('2fa_temp_secret');

        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication has been enabled successfully!',
            'recovery_codes' => $user->getRecoveryCodesAttribute()
        ]);
    }

    /**
     * Disable two-factor authentication.
     */
    public function disable(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string']
        ]);

        $user = auth()->user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided password is incorrect.'
            ], 400);
        }

        if (!$user->hasTwoFactorEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Two-factor authentication is not enabled.'
            ], 400);
        }

        // Disable 2FA
        $user->disableTwoFactor();

        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication has been disabled.'
        ]);
    }

    /**
     * Generate new recovery codes.
     */
    public function generateRecoveryCodes(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string']
        ]);

        $user = auth()->user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided password is incorrect.'
            ], 400);
        }

        if (!$user->hasTwoFactorEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Two-factor authentication is not enabled.'
            ], 400);
        }

        // Generate new recovery codes
        $codes = $user->generateRecoveryCodes();

        return response()->json([
            'success' => true,
            'message' => 'New recovery codes have been generated.',
            'recovery_codes' => $codes
        ]);
    }

    /**
     * Show recovery codes.
     */
    public function showRecoveryCodes(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string']
        ]);

        $user = auth()->user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided password is incorrect.'
            ], 400);
        }

        if (!$user->hasTwoFactorEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Two-factor authentication is not enabled.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'recovery_codes' => $user->getRecoveryCodesAttribute()
        ]);
    }

    /**
     * Test two-factor authentication code.
     */
    public function test(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6']
        ]);

        $user = auth()->user();

        if (!$user->hasTwoFactorEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Two-factor authentication is not enabled.'
            ], 400);
        }

        $secret = $user->getTwoFactorSecret();
        $valid = $this->google2fa->verifyKey($secret, $request->code);

        return response()->json([
            'success' => $valid,
            'message' => $valid ? 'Code is valid!' : 'Code is invalid.'
        ]);
    }
}
