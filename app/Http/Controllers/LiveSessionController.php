<?php

namespace App\Http\Controllers;

use App\Models\LiveSession;
use App\Models\SessionRegistration;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class LiveSessionController extends Controller
{
    public function index(): View
    {
        $upcomingSessions = LiveSession::active()->upcoming()->ordered()->with(['presenters'])->get();
        $pastSessions = LiveSession::completed()->ordered()->paginate(12);

        $heroImage = get_setting('hero_background_academy') ? get_image(get_setting('hero_background_academy')) : null;
        $title = 'Live Sessies & Webinars';
        $subtitle = 'Neem deel aan interactieve sessies met experts, stel je vragen en leer van praktijkvoorbeelden.';

        return view('front.academy.live-sessions.index', compact('upcomingSessions', 'pastSessions', 'heroImage', 'title', 'subtitle'));
    }

    public function recordings(): View
    {
        $pastSessions = LiveSession::completed()->ordered()->paginate(12);

        $heroImage = get_setting('hero_background_academy') ? get_image(get_setting('hero_background_academy')) : null;
        $title = __('frontend.academy.recent_recordings');
        $subtitle = __('frontend.academy.recordings_subtitle');

        return view('front.academy.live-sessions.recordings', compact('pastSessions', 'heroImage', 'title', 'subtitle'));
    }

    public function show(LiveSession $liveSession): View
    {
        // Ensure we can show details even if it's inactive for admins, or handle 404 via model binding logic if restricted
        if (!$liveSession->is_active && !auth()->guard('admin')->check()) {
            abort(404);
        }

        $heroImage = $liveSession->thumbnail ? Storage::url($liveSession->thumbnail) : (get_setting('hero_background_academy') ? get_image(get_setting('hero_background_academy')) : null);
        $title = $liveSession->title;
        $subtitle = $liveSession->description;

        return view('front.academy.live-sessions.show', compact('liveSession', 'heroImage', 'title', 'subtitle'));
    }

    public function store(Request $request, LiveSession $liveSession)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'organization' => 'required|string|max:255',
            'marketing_consent' => 'nullable|boolean',
        ]);

        $registration = new SessionRegistration($validated);
        $registration->live_session_id = $liveSession->id;
        $registration->status = 'registered';
        $registration->marketing_consent = $request->has('marketing_consent');
        $registration->save();

        return redirect()->route('academy.live-sessions.show', $liveSession)
            ->with('success', 'Je bent succesvol geregistreerd voor deze sessie!');
    }
}
