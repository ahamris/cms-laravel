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
     */
    public function create()
    {
        return view('admin.homepage-faq.create');
    }

    /**
     * Store a newly created FAQ group in storage.
     */
    public function store(HomepageFaqRequest $request)
    {
        $data = $request->validated();
        $data = $this->purifyHtmlKeys($data, ['items.*.answer']);

        Faq::create($data);

        return redirect()->route('admin.content.faq-module.index')
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

        $faq->update($data);

        return redirect()->route('admin.content.faq-module.index')
                        ->with('success', 'FAQ Group updated successfully.');
    }

    /**
     * Remove the specified FAQ group from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.content.faq-module.index')
                        ->with('success', 'FAQ Group deleted successfully.');
    }
}
