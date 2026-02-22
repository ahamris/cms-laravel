<?php

namespace App\Http\Controllers\Admin\Administrator;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\View\View;

class ContactController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.administrator.contacts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.administrator.contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request)
    {
        $validated = $request->validated();

        $contact = Contact::create($validated);

        // Check if user wants to continue editing
        if ($request->input('action') === 'save_and_stay') {
            return redirect()->route('admin.administrator.contacts.edit', $contact)
                ->with('success', 'Contact created successfully! You can continue editing.');
        }

        return redirect()->route('admin.administrator.contacts.index')
            ->with('success', 'Contact created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact): View
    {
        return view('admin.administrator.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact): View
    {
        return view('admin.administrator.contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $request, Contact $contact)
    {
        $validated = $request->validated();

        $contact->update($validated);

        // Check if user wants to continue editing
        if ($request->input('action') === 'save_and_stay') {
            return redirect()->route('admin.administrator.contacts.edit', $contact)
                ->with('success', 'Contact updated successfully! You can continue editing.');
        }

        return redirect()->route('admin.administrator.contacts.index')
            ->with('success', 'Contact updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.administrator.contacts.index')
            ->with('success', 'Contact deleted successfully!');
    }

    /**
     * Toggle contact active status
     */
    public function toggleActive(Contact $contact)
    {
        $contact->update(['is_active' => ! $contact->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $contact->is_active,
            'message' => $contact->is_active ? 'Contact activated successfully!' : 'Contact deactivated successfully!',
        ]);
    }
}
