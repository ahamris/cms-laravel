<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\HomepageFaqRequest;
use App\Models\Faq;

class HomepageFaqController extends AdminBaseController
{
    /**
     * Display a listing of FAQ groups.
     */
    public function index()
    {
        $faqs = Faq::whereNotNull('identifier')
                   ->orderBy('id', 'desc')
                   ->paginate(20);

        return view('admin.homepage-faq.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new FAQ group.
     * Adding new FAQ groups is not permitted; only the contact page FAQ (identifier: contact) is managed.
     */
    public function create()
    {
        $contactFaq = Faq::where('identifier', 'contact')->first();
        if ($contactFaq) {
            return redirect()->route('admin.faq-module.edit', $contactFaq)
                ->with('info', 'Only the contact page FAQ can be edited. Adding new FAQ groups is not permitted.');
        }
        return redirect()->route('admin.faq-module.index')
            ->with('info', 'Adding new FAQ groups is not permitted. Run the seeder to create the contact FAQ.');
    }

    /**
     * Store a newly created FAQ group in storage.
     */
    public function store(HomepageFaqRequest $request)
    {
        if (Faq::where('identifier', $request->input('identifier', 'contact'))->exists()) {
            return redirect()->route('admin.faq-module.index')
                ->with('error', 'Adding new FAQ groups is not permitted. Edit the existing contact FAQ instead.');
        }

        $data = $request->validated();
        $data = $this->purifyHtmlKeys($data, ['items.*.answer']);
        $data['items'] = array_values($data['items']);

        Faq::create($data);

        return redirect()->route('admin.faq-module.index')
                        ->with('success', 'FAQ Group created successfully.');
    }

    /**
     * Display the specified FAQ group.
     */
    public function show(Faq $faq)
    {
        return view('admin.homepage-faq.show', compact('faq'));
    }

    /**
     * Show the form for editing the specified FAQ group.
     */
    public function edit(Faq $faq)
    {
        return view('admin.homepage-faq.edit', compact('faq'));
    }

    /**
     * Update the specified FAQ group in storage.
     */
    public function update(HomepageFaqRequest $request, Faq $faq)
    {
        $data = $request->validated();
        $data = $this->purifyHtmlKeys($data, ['items.*.answer']);
        $data['items'] = array_values($data['items']);

        $faq->update($data);

        return redirect()->route('admin.faq-module.index')
                        ->with('success', 'FAQ Group updated successfully.');
    }

    /**
     * Remove the specified FAQ group from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faq-module.index')
                        ->with('success', 'FAQ Group deleted successfully.');
    }
}
