<?php

namespace App\Http\Controllers\Admin;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormBuilderController extends AdminBaseController
{
    public function index(): View
    {
        return view('admin.forms.index');
    }

    public function create(): View
    {
        $fieldTypes = FormField::supportedTypes();

        return view('admin.forms.builder', [
            'form'       => null,
            'fieldTypes' => $fieldTypes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:200',
            'slug'                => 'nullable|string|max:200|unique:forms,slug',
            'description'         => 'nullable|string',
            'type'                => 'required|string|in:contact,lead,support,survey,newsletter,custom',
            'success_message'     => 'nullable|string',
            'redirect_url'        => 'nullable|url|max:500',
            'notification_emails' => 'nullable|string|max:500',
            'notification_slack'  => 'nullable|url|max:500',
            'honeypot_field'      => 'nullable|string|max:50',
            'recaptcha_enabled'   => 'nullable|boolean',
            'crm_pipeline'        => 'nullable|string|max:30',
            'crm_auto_contact'    => 'nullable|boolean',
            'crm_auto_deal'       => 'nullable|boolean',
            'crm_deal_value'      => 'nullable|integer|min:0',
            'is_active'           => 'nullable|boolean',
            'fields'              => 'nullable|array',
            'fields.*.name'       => 'required|string|max:100',
            'fields.*.label'      => 'required|string|max:200',
            'fields.*.type'       => 'required|string|max:30',
            'fields.*.placeholder' => 'nullable|string|max:200',
            'fields.*.help_text'  => 'nullable|string|max:500',
            'fields.*.is_required' => 'nullable|boolean',
            'fields.*.options'    => 'nullable|array',
            'fields.*.width'      => 'nullable|string|in:full,half',
            'fields.*.crm_map_to' => 'nullable|string|max:50',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['recaptcha_enabled'] = $request->boolean('recaptcha_enabled', false);
        $validated['crm_auto_contact'] = $request->boolean('crm_auto_contact', true);
        $validated['crm_auto_deal'] = $request->boolean('crm_auto_deal', false);

        $fields = $validated['fields'] ?? [];
        unset($validated['fields']);

        $form = Form::create($validated);

        foreach ($fields as $index => $field) {
            $form->fields()->create(array_merge($field, [
                'sort_order'  => $index,
                'is_required' => !empty($field['is_required']),
            ]));
        }

        $this->logActivity('form', 'created', "Created form: {$form->name}");

        return redirect()->route('admin.form.index')
            ->with('success', 'Form created successfully.');
    }

    public function show(Form $form): View
    {
        $form->load('fields');
        $form->loadCount('submissions');

        return view('admin.forms.show', compact('form'));
    }

    public function edit(Form $form): View
    {
        $form->load('fields');
        $fieldTypes = FormField::supportedTypes();

        return view('admin.forms.builder', compact('form', 'fieldTypes'));
    }

    public function update(Request $request, Form $form)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:200',
            'slug'                => 'nullable|string|max:200|unique:forms,slug,' . $form->id,
            'description'         => 'nullable|string',
            'type'                => 'required|string|in:contact,lead,support,survey,newsletter,custom',
            'success_message'     => 'nullable|string',
            'redirect_url'        => 'nullable|url|max:500',
            'notification_emails' => 'nullable|string|max:500',
            'notification_slack'  => 'nullable|url|max:500',
            'honeypot_field'      => 'nullable|string|max:50',
            'recaptcha_enabled'   => 'nullable|boolean',
            'crm_pipeline'        => 'nullable|string|max:30',
            'crm_auto_contact'    => 'nullable|boolean',
            'crm_auto_deal'       => 'nullable|boolean',
            'crm_deal_value'      => 'nullable|integer|min:0',
            'is_active'           => 'nullable|boolean',
            'fields'              => 'nullable|array',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['recaptcha_enabled'] = $request->boolean('recaptcha_enabled', false);
        $validated['crm_auto_contact'] = $request->boolean('crm_auto_contact', true);
        $validated['crm_auto_deal'] = $request->boolean('crm_auto_deal', false);

        $fields = $validated['fields'] ?? [];
        unset($validated['fields']);

        $form->update($validated);

        $form->fields()->delete();
        foreach ($fields as $index => $field) {
            $form->fields()->create(array_merge($field, [
                'sort_order'  => $index,
                'is_required' => !empty($field['is_required']),
            ]));
        }

        $this->logActivity('form', 'updated', "Updated form: {$form->name}");

        return redirect()->route('admin.form.index')
            ->with('success', 'Form updated successfully.');
    }

    public function destroy(Form $form)
    {
        $name = $form->name;
        $form->delete();

        $this->logActivity('form', 'deleted', "Deleted form: {$name}");

        return redirect()->route('admin.form.index')
            ->with('success', 'Form deleted successfully.');
    }

    public function duplicate(Form $form)
    {
        $newForm = $form->replicate();
        $newForm->name = $form->name . ' (Copy)';
        $newForm->slug = null;
        $newForm->save();

        foreach ($form->fields as $field) {
            $newField = $field->replicate();
            $newField->form_id = $newForm->id;
            $newField->save();
        }

        return redirect()->route('admin.form.edit', $newForm)
            ->with('success', 'Form duplicated successfully.');
    }
}
