<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorChallengeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a two factor authentication challenge.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = $request->session()->get('login.id')
            ? \App\Models\User::find($request->session()->get('login.id'))
            : null;

        if (!$user) {
            return response()->json(['message' => 'Invalid session'], 422);
        }

        // Check if user has 2FA enabled
        if (!$user->two_factor_secret) {
            return response()->json(['message' => 'Two factor authentication is not enabled'], 422);
        }

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            decrypt($user->two_factor_secret),
            $request->code
        );

        if (!$valid) {
            // Try recovery code as fallback
            if ($user->useRecoveryCode($request->code)) {
                Auth::login($user, $request->session()->get('login.remember', false));

                $request->session()->forget([
                    'login.id',
                    'login.remember',
                ]);

                $request->session()->put('two_factor_authenticated', true);

                return response()->json([
                    'message' => 'Two factor authentication successful (recovery code used)',
                    'redirect' => '/admin'
                ], 200);
            }

            return response()->json(['message' => 'The provided two factor authentication code was invalid'], 422);
        }

        Auth::login($user, $request->session()->get('login.remember', false));

        $request->session()->forget([
            'login.id',
            'login.remember',
        ]);

        $request->session()->put('two_factor_authenticated', true);

        return response()->json([
            'message' => 'Two factor authentication successful',
            'redirect' => '/admin'
        ], 200);
    }
}
