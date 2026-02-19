<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language
     */
    public function switch(Request $request): RedirectResponse
    {
        $locale = $request->input('locale');
        $supportedLocales = config('app.locales', ['nl', 'en']);
        
        // Validate the locale
        if (!in_array($locale, $supportedLocales)) {
            return redirect()->back()->with('error', 'Unsupported language selected.');
        }
        
        // Store the locale in session
        Session::put('locale', $locale);
        
        // Set the application locale immediately
        app()->setLocale($locale);
        
        // Redirect back to the previous page
        return redirect()->back()->with('success', 'Language changed successfully.');
    }
}
