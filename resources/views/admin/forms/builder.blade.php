<x-layouts.admin title="{{ $form ? 'Edit Form: ' . $form->name : 'Create Form' }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-zinc-900 dark:text-white mb-2">{{ $form ? 'Edit Form' : 'Create Form' }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ $form ? 'Update form configuration and fields' : 'Build a new dynamic form' }}</p>
            </div>
            <a href="{{ route('admin.form.index') }}"
                class="inline-flex items-center gap-2 rounded-md border border-zinc-300 dark:border-zinc-600 px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>

        <form action="{{ $form ? route('admin.form.update', $form) : route('admin.form.store') }}" method="POST" class="space-y-6">
            @csrf
            @if($form) @method('PUT') @endif

            {{-- Form Settings --}}
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
                <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Form Settings</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.form-field name="name" label="Form Name" :value="$form?->name" required />
                    <x-form.form-field name="slug" label="Slug" :value="$form?->slug" helper="Leave empty to auto-generate" />
                    <x-form.form-field name="type" label="Type" type="select" :value="$form?->type ?? 'contact'"
                        :options="['contact' => 'Contact', 'lead' => 'Lead', 'support' => 'Support', 'survey' => 'Survey', 'newsletter' => 'Newsletter', 'custom' => 'Custom']" />
                    <x-form.form-field name="notification_emails" label="Notification Emails" :value="$form?->notification_emails" placeholder="admin@example.com, team@example.com" />
                    <div class="md:col-span-2">
                        <x-form.form-field name="description" label="Description" type="textarea" :value="$form?->description" />
                    </div>
                    <div class="md:col-span-2">
                        <x-form.form-field name="success_message" label="Success Message" type="textarea" :value="$form?->success_message" placeholder="Thank you for your submission." />
                    </div>
                </div>
            </div>

            {{-- CRM Integration --}}
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
                <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">CRM Integration</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.form-field name="crm_pipeline" label="Pipeline Stage" type="select" :value="$form?->crm_pipeline"
                        :options="['' => 'None', 'interesseer' => 'Attract (Interesseer)', 'overtuig' => 'Convert (Overtuig)', 'activeer' => 'Close (Activeer)', 'inspireer' => 'Delight (Inspireer)']" />
                    <x-form.form-field name="crm_deal_value" label="Default Deal Value (cents)" type="number" :value="$form?->crm_deal_value" />
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="crm_auto_contact" value="1" {{ old('crm_auto_contact', $form?->crm_auto_contact ?? true) ? 'checked' : '' }} class="rounded border-zinc-300 dark:border-zinc-600">
                            <span class="text-zinc-700 dark:text-zinc-300">Auto-create Contact</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="crm_auto_deal" value="1" {{ old('crm_auto_deal', $form?->crm_auto_deal ?? false) ? 'checked' : '' }} class="rounded border-zinc-300 dark:border-zinc-600">
                            <span class="text-zinc-700 dark:text-zinc-300">Auto-create Deal</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Form Fields --}}
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6" x-data="formBuilder()">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-zinc-900 dark:text-white">Form Fields</h2>
                    <button type="button" @click="addField()"
                        class="inline-flex items-center gap-2 rounded-md bg-zinc-100 dark:bg-zinc-700 px-3 py-1.5 font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors">
                        <i class="fa-solid fa-plus"></i>
                        Add Field
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(field, index) in fields" :key="index">
                        <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-medium text-zinc-700 dark:text-zinc-300" x-text="'Field ' + (index + 1)"></span>
                                <button type="button" @click="removeField(index)" class="text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block font-medium text-zinc-600 dark:text-zinc-400 mb-1">Name</label>
                                    <input type="text" :name="'fields[' + index + '][name]'" x-model="field.name" required
                                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block font-medium text-zinc-600 dark:text-zinc-400 mb-1">Label</label>
                                    <input type="text" :name="'fields[' + index + '][label]'" x-model="field.label" required
                                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block font-medium text-zinc-600 dark:text-zinc-400 mb-1">Type</label>
                                    <select :name="'fields[' + index + '][type]'" x-model="field.type"
                                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                                        @foreach($fieldTypes as $type)
                                            <option value="{{ $type }}">{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-medium text-zinc-600 dark:text-zinc-400 mb-1">Placeholder</label>
                                    <input type="text" :name="'fields[' + index + '][placeholder]'" x-model="field.placeholder"
                                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block font-medium text-zinc-600 dark:text-zinc-400 mb-1">Width</label>
                                    <select :name="'fields[' + index + '][width]'" x-model="field.width"
                                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                                        <option value="full">Full</option>
                                        <option value="half">Half</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <label class="flex items-center gap-2 pb-2">
                                        <input type="checkbox" :name="'fields[' + index + '][is_required]'" value="1" :checked="field.is_required"
                                            class="rounded border-zinc-300 dark:border-zinc-600">
                                        <span class="text-zinc-700 dark:text-zinc-300">Required</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $form?->is_active ?? true) ? 'checked' : '' }} class="rounded border-zinc-300 dark:border-zinc-600">
                    <span class="text-zinc-700 dark:text-zinc-300">Active</span>
                </label>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-6 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
                    <i class="fa-solid fa-check"></i>
                    {{ $form ? 'Update Form' : 'Create Form' }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function formBuilder() {
            return {
                fields: @json($form?->fields?->map(fn($f) => [
                    'name' => $f->name,
                    'label' => $f->label,
                    'type' => $f->type,
                    'placeholder' => $f->placeholder,
                    'width' => $f->width,
                    'is_required' => $f->is_required,
                ]) ?? []),
                addField() {
                    this.fields.push({ name: '', label: '', type: 'text', placeholder: '', width: 'full', is_required: false });
                },
                removeField(index) {
                    this.fields.splice(index, 1);
                }
            };
        }
    </script>
    @endpush
</x-layouts.admin>
