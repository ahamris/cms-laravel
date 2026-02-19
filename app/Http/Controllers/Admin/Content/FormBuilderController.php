<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\FormBuilderRequest;
use App\Models\FormBuilder;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class FormBuilderController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = FormBuilder::withCount('submissions')
            ->ordered()
            ->paginate(20);

        return view('admin.content.form-builder.index', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.content.form-builder.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FormBuilderRequest $request)
    {
        $data = $request->validated();
        $data = $this->purifyHtmlKeys($data, ['description']);
        
        // Parse fields JSON if provided
        if ($request->has('fields')) {
            $data['fields'] = json_decode($request->fields, true);
        }

        $formBuilder = FormBuilder::create($data);

        $this->logCreate($formBuilder);

        return redirect()
            ->route('admin.content.form-builder.index')
            ->with('success', 'Form created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FormBuilder $formBuilder)
    {
        $formBuilder->loadCount('submissions');
        $recentSubmissions = $formBuilder->submissions()
            ->latest()
            ->take(10)
            ->get();

        return view('admin.content.form-builder.show', compact('formBuilder', 'recentSubmissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormBuilder $formBuilder)
    {
        return view('admin.content.form-builder.edit', compact('formBuilder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FormBuilderRequest $request, FormBuilder $formBuilder)
    {
        $data = $request->validated();
        $data = $this->purifyHtmlKeys($data, ['description']);
        
        // Parse fields JSON if provided
        if ($request->has('fields')) {
            $data['fields'] = json_decode($request->fields, true);
        }

        $formBuilder->update($data);

        $this->logUpdate($formBuilder);

        return redirect()
            ->route('admin.content.form-builder.index')
            ->with('success', 'Form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormBuilder $formBuilder)
    {
        $formBuilder->delete();

        $this->logDelete($formBuilder);

        return redirect()
            ->route('admin.content.form-builder.index')
            ->with('success', 'Form deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(FormBuilder $formBuilder)
    {
        $formBuilder->update(['is_active' => !$formBuilder->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $formBuilder->is_active,
        ]);
    }

    /**
     * View submissions for a form
     */
    public function submissions(FormBuilder $formBuilder)
    {
        $submissions = $formBuilder->submissions()
            ->latest()
            ->paginate(20);

        return view('admin.content.form-builder.submissions', compact('formBuilder', 'submissions'));
    }

    /**
     * View single submission
     */
    public function viewSubmission(FormBuilder $formBuilder, FormSubmission $submission)
    {
        $submission->markAsRead();

        return view('admin.content.form-builder.view-submission', compact('formBuilder', 'submission'));
    }

    /**
     * Delete submission
     */
    public function deleteSubmission(FormBuilder $formBuilder, FormSubmission $submission)
    {
        $submission->delete();

        return redirect()
            ->route('admin.content.form-builder.submissions', $formBuilder)
            ->with('success', 'Submission deleted successfully.');
    }

    /**
     * Update order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:form_builders,id',
        ]);

        foreach ($request->order as $index => $id) {
            FormBuilder::where('id', $id)->update(['sort_order' => $index]);
        }

        $this->logOrderUpdate('FormBuilder', count($request->order));

        return response()->json(['success' => true]);
    }
}
