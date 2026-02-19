<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA verification form.
     */
    public function show()
    {
        if (!session('login.id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.two-factor-challenge');
    }

    /**
     * Verify the 2FA code and complete login.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $userId = session('login.id');
        if (!$userId) {
            return redirect()->route('admin.login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('admin.login');
        }

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);

        // Verify the 2FA code
        $valid = $google2fa->verifyKey($secret, $request->code);

        if (!$valid) {
            return back()->withErrors([
                'code' => 'The provided two-factor authentication code was invalid.'
            ]);
        }

        // Complete the login
        Auth::login($user, session('login.remember', false));

        // Clear the session data
        session()->forget(['login.id', 'login.remember']);

        return redirect()->intended('/admin');
    }

    /**
     * Show recovery codes form.
     */
    public function showRecovery()
    {
        if (!session('login.id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.two-factor-recovery');
    }

    /**
     * Verify recovery code and complete login.
     */
    public function verifyRecovery(Request $request)
    {
        $request->validate([
            'recovery_code' => ['required', 'string'],
        ]);

        $userId = session('login.id');
        if (!$userId) {
            return redirect()->route('admin.login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Use the recovery code
        if (!$user->useRecoveryCode($request->recovery_code)) {
            return back()->withErrors([
                'recovery_code' => 'The provided recovery code was invalid.'
            ]);
        }

        // Complete the login
        Auth::login($user, session('login.remember', false));

        // Clear the session data
        session()->forget(['login.id', 'login.remember']);

        return redirect()->intended('/admin');
    }
}
