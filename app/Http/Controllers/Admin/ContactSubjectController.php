<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactSubjectRequest;
use App\Models\ContactSubject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactSubjectController extends AdminBaseController
{
    public function index(): View
    {
        $subjects = ContactSubject::ordered()->get();

        return view('admin.contact_subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        return view('admin.contact_subjects.create');
    }

    public function store(ContactSubjectRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        ContactSubject::create($data);

        return redirect()->route('admin.administrator.contact-subjects.index')
            ->with('success', __('Subject created successfully.'));
    }

    public function edit(ContactSubject $contactSubject): View
    {
        return view('admin.contact_subjects.edit', compact('contactSubject'));
    }

    public function update(ContactSubjectRequest $request, ContactSubject $contactSubject): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        $contactSubject->update($data);

        return redirect()->route('admin.administrator.contact-subjects.index')
            ->with('success', __('Subject updated successfully.'));
    }

    public function destroy(ContactSubject $contactSubject): RedirectResponse
    {
        $contactSubject->delete();

        return redirect()->route('admin.administrator.contact-subjects.index')
            ->with('success', __('Subject deleted successfully.'));
    }
}
