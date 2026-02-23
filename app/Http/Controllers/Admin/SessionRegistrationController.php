<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\SessionRegistrationRequest;
use App\Models\SessionRegistration;
use App\Models\LiveSession;
use Illuminate\Http\Request;

class SessionRegistrationController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     * Table is rendered by Livewire admin.table component (search: name, email, organization).
     */
    public function index()
    {
        return view('admin.session-registration.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $liveSessions = LiveSession::active()->ordered()->get();
        $selectedSessionId = $request->get('session_id');

        return view('admin.session-registration.create', compact('liveSessions', 'selectedSessionId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SessionRegistrationRequest $request)
    {
        SessionRegistration::create($request->validated());

        return redirect()
            ->route('admin.content.session-registration.index')
            ->with('success', 'Registratie succesvol aangemaakt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SessionRegistration $sessionRegistration)
    {
        $sessionRegistration->load('liveSession');

        return view('admin.session-registration.show', compact('sessionRegistration'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SessionRegistration $sessionRegistration)
    {
        $liveSessions = LiveSession::active()->ordered()->get();
        $sessionRegistration->load('liveSession');

        return view('admin.session-registration.edit', compact('sessionRegistration', 'liveSessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SessionRegistrationRequest $request, SessionRegistration $sessionRegistration)
    {
        $data = $request->validated();

        // Set attended_at timestamp when marking as attended
        if ($data['status'] === 'attended' && $sessionRegistration->status !== 'attended') {
            $data['attended_at'] = now();
        } elseif ($data['status'] !== 'attended') {
            $data['attended_at'] = null;
        }

        $sessionRegistration->update($data);

        return redirect()
            ->route('admin.content.session-registration.index')
            ->with('success', 'Registratie succesvol bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SessionRegistration $sessionRegistration)
    {
        $sessionRegistration->delete();

        return redirect()
            ->route('admin.content.session-registration.index')
            ->with('success', 'Registratie succesvol verwijderd.');
    }

    /**
     * Mark registration as attended.
     */
    public function markAttended(SessionRegistration $sessionRegistration)
    {
        $sessionRegistration->markAsAttended();

        return redirect()
            ->back()
            ->with('success', 'Deelnemer gemarkeerd als aanwezig.');
    }

    /**
     * Mark registration as no show.
     */
    public function markNoShow(SessionRegistration $sessionRegistration)
    {
        $sessionRegistration->markAsNoShow();

        return redirect()
            ->back()
            ->with('success', 'Deelnemer gemarkeerd als niet verschenen.');
    }

    /**
     * Cancel registration.
     */
    public function cancel(SessionRegistration $sessionRegistration)
    {
        $sessionRegistration->cancel();

        return redirect()
            ->back()
            ->with('success', 'Registratie geannuleerd.');
    }

    /**
     * Export registrations for a session.
     */
    public function export(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:live_sessions,id',
            'format' => 'required|in:csv,xlsx',
        ]);

        $session = LiveSession::findOrFail($request->session_id);
        $registrations = $session->registrations()->get();

        $filename = "registrations_{$session->slug}." . $request->format;

        if ($request->format === 'csv') {
            return $this->exportCsv($registrations, $filename);
        }

        // For future XLSX implementation
        return redirect()->back()->with('error', 'XLSX export not yet implemented.');
    }

    /**
     * Export registrations as CSV.
     */
    private function exportCsv($registrations, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Naam',
                'E-mail',
                'Organisatie',
                'Status',
                'Geregistreerd op',
                'Aanwezig op',
                'Marketing toestemming',
                'Notities'
            ]);

            // CSV data
            foreach ($registrations as $registration) {
                fputcsv($file, [
                    $registration->name,
                    $registration->email,
                    $registration->organization,
                    $registration->status_display,
                    $registration->registered_at->format('d-m-Y H:i'),
                    $registration->attended_at ? $registration->attended_at->format('d-m-Y H:i') : '',
                    $registration->marketing_consent ? 'Ja' : 'Nee',
                    $registration->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
