# Admin Panel Development Guide

This documentation explains how the admin panel is developed, which components are used, and how encountered issues are resolved.

## Table of Contents

1. [Layout Usage](#layout-usage)
2. [Page Structure](#page-structure)
3. [UI Components](#ui-components)
4. [Form Elements](#form-elements)
5. [Table Usage](#table-usage)
6. [Drawer (Side Panel) Usage](#drawer-side-panel-usage)
7. [Modal Usage](#modal-usage)
8. [Colors and Theme](#colors-and-theme)
9. [Resolved Issues](#resolved-issues)
10. [Important Notes](#important-notes)

---

## Layout Usage

### Old Layout (Should Not Be Used)

```blade
@extends('admin.layouts.app')
@section('title', 'Page Title')
@section('content')
    {{-- content --}}
@endsection
@push('scripts')
    <script>/* script */</script>
@endpush
```

### New Layout (Should Be Used)

```blade
<x-layouts.admin title="Page Title">
    {{-- content directly here --}}

    <script>
        // scripts inline at the end of the file
    </script>
</x-layouts.admin>
```

**Important:** `@push('scripts')` and `@push('styles')` should not be used; they should be written inline within the component.

---

## Page Structure

### Simple List Page (Index)

```blade
<x-layouts.admin title="Page Title">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Page Title</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Description text here</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('...') }}">
                    Add New Item
                </x-button>
            </div>
        </div>

        {{-- Content --}}
        <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">
            {{-- Table or content --}}
        </div>
    </div>
</x-layouts.admin>
```

### Two-Column Page (Create/Edit/Show)

```blade
<x-layouts.admin title="Page Title">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Page Title</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Description</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            {{-- Action Buttons --}}
            <a href="{{ route('...') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
            <a href="{{ route('...') }}" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90">
                <i class="fa-solid fa-edit"></i>
                Edit
            </a>
        </div>
    </div>

    {{-- 2/3 - 1/3 Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - 2/3 --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Cards --}}
        </div>

        {{-- Right Column - 1/3 --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Cards --}}
        </div>
    </div>
</x-layouts.admin>
```

---

## UI Components

### Card

```blade
<div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
    <div class="mb-6">
        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Card Title</h2>
        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Card description</p>
    </div>

    <div class="space-y-6">
        {{-- Card content --}}
    </div>
</div>
```

### Badge

```blade
{{-- Status Badge --}}
<span class="inline-flex items-center gap-x-1.5 rounded-full bg-green-100 dark:bg-green-500/10 px-3 py-1.5 text-sm font-medium text-green-700 dark:text-green-400">
    <svg class="size-1.5 fill-current" viewBox="0 0 6 6"><circle cx="3" cy="3" r="3" /></svg>
    Active
</span>

{{-- Color Options --}}
{{-- green: Active/Success --}}
{{-- red: Inactive/Error --}}
{{-- yellow: Pending/Warning --}}
{{-- blue: New/Info --}}
{{-- gray: Closed/Default --}}
{{-- purple: Special cases --}}
```

### Avatar

```blade
<div class="size-10 rounded-full bg-[var(--color-accent)]/10 flex items-center justify-center">
    <span class="text-sm font-medium text-[var(--color-accent)]">{{ substr($name, 0, 1) }}</span>
</div>
```

### Empty State

```blade
<div class="text-center py-12">
    <div class="size-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center">
        <i class="fa-solid fa-icon-name text-2xl text-gray-400 dark:text-gray-500"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No items found</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400">Description text here.</p>
</div>
```

---

## Form Elements

### Input

```blade
<div>
    <label for="field_name" class="block text-sm/6 font-medium text-gray-900 dark:text-white">
        Label <span class="text-red-500">*</span>
    </label>
    <div class="mt-2">
        <input type="text"
               id="field_name"
               name="field_name"
               value="{{ old('field_name', $model->field_name ?? '') }}"
               placeholder="Placeholder text"
               required
               class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('field_name') outline-red-500 dark:outline-red-500 @enderror">
    </div>
    @error('field_name')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
</div>
```

### Textarea

```blade
<div>
    <label for="description" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Description</label>
    <div class="mt-2">
        <textarea id="description"
                  name="description"
                  rows="4"
                  placeholder="Enter description..."
                  class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)]">{{ old('description', $model->description ?? '') }}</textarea>
    </div>
</div>
```

### Select

```blade
<div>
    <label for="category_id" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Category</label>
    <div class="mt-2 grid grid-cols-1">
        <select id="category_id"
                name="category_id"
                required
                class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-[var(--color-accent)]">
            <option value="">Select an option</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $model->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <svg viewBox="0 0 16 16" fill="currentColor" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
            <path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
        </svg>
    </div>
</div>
```

### Toggle (x-ui.toggle)

Use **`x-ui.toggle`** (or `x-toggle`). The component includes a hidden input so the form receives `0` when unchecked and `1` when checked.

```blade
<x-ui.toggle
    name="is_active"
    label="Active"
    :checked="old('is_active', $model->is_active ?? true)"
/>
```

### Editor

Use **`x-editor`** for rich text content (replaces the old Quill/admin editor).

```blade
<div>
    <label for="content" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Content <span class="text-red-500">*</span></label>
    <div class="mt-2">
        <x-editor
            id="content"
            name="content"
            :value="old('content', $model->content ?? '')"
            placeholder="Write content here..."
        />
    </div>
    @error('content')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
</div>
```

### Slug Input

Use `x-ui.input` with `slug-from` (ID of the source field, e.g. title). Slug is auto-generated from the source and updates until the user edits the slug manually.

```blade
<x-ui.input
    id="slug"
    name="slug"
    :value="old('slug', $model->slug ?? '')"
    slug-from="title"
    label="Slug"
    placeholder="url-friendly-slug"
    hint="URL-friendly version of the title. Auto-generated if left blank."
/>
```

### Image Upload

```blade
<x-image-upload
    id="image"
    name="image"
    label=""
    :required="false"
    help-text="PNG, JPG, GIF up to 2MB"
    :max-size="2048"
    :current-image="$model->image ? Storage::disk('public')->url($model->image) : null"
    :current-image-alt="$model->title ?? 'Image'"
/>
```

---

## Table Usage

### Livewire Table (Recommended)

```blade
<livewire:admin.table
    resource="model-name"
    :columns="[
        'title',
        ['key' => 'category.name', 'label' => 'Category'],
        ['key' => 'created_at', 'format' => 'date'],
        ['key' => 'is_active', 'type' => 'toggle'],
        ['key' => 'custom_column', 'type' => 'custom', 'view' => 'admin.model.partials.custom-column'],
    ]"
    route-prefix="admin.model"
    search-placeholder="Search..."
    :paginate="15"
    :search-fields="['title', 'slug']"
/>
```

**Table row toggle (is_active) and 403 errors:** If clicking the is_active toggle in a table row returns 403:

- The `columns` array passed to the table **must** define that field as a toggle, e.g. `['key' => 'is_active', 'type' => 'toggle']` (or with a `label`). The Livewire `Table` component only allows toggle updates for columns explicitly marked with `'type' => 'toggle'`.
- Use Livewire mode (do not rely on external/provided items without passing columns).
- Pass columns explicitly: `:columns="$columns"` so the component receives the same config on the initial render and on subsequent Livewire requests.

See `app\Livewire\Admin\Table.php` → `toggleField()` method and its docblock for the exact validation rules.

### Manual Table

```blade
<div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Column</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-transparent divide-y divide-gray-200 dark:divide-white/10">
                @foreach($items as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $item->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Action buttons --}}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
```

---

## Drawer (Side Panel) Usage

### x-ui.drawer Component

```blade
{{-- Button to open drawer --}}
<x-button variant="primary" command="show-modal" command-target="drawer-id">
    Open Drawer
</x-button>

{{-- Drawer --}}
<x-ui.drawer id="drawer-id" title="Drawer Title" position="right" size="lg">
    {{-- Drawer content --}}
    <form action="{{ route('...') }}" method="POST">
        @csrf
        {{-- Form fields --}}
    </form>

    <x-slot:footer>
        <x-button variant="secondary" command="close">Cancel</x-button>
        <x-button variant="primary" type="submit" form="form-id">Save</x-button>
    </x-slot:footer>
</x-ui.drawer>
```

---

## Modal Usage

### x-ui.modal Component

```blade
{{-- Control with Alpine.js --}}
<div x-data="{ showModal: false }">
    <button @click="showModal = true">Open Modal</button>

    <x-ui.modal modal-id="modal-id" size="lg" alpine-show="showModal">
        <x-slot:title>Modal Title</x-slot:title>

        {{-- Modal content --}}

        <x-slot:footer>
            <x-button variant="secondary" x-on:click="showModal = false">Cancel</x-button>
            <x-button variant="primary">Confirm</x-button>
        </x-slot:footer>
    </x-ui.modal>
</div>
```

---

## Colors and Theme

### Accent Color Usage

```blade
{{-- As CSS Variable --}}
bg-[var(--color-accent)]
text-[var(--color-accent)]
outline-[var(--color-accent)]
focus:outline-[var(--color-accent)]

{{-- With opacity --}}
bg-[var(--color-accent)]/10
```

### Dark Mode Support

```blade
{{-- dark: prefix should be used for every element --}}
text-gray-900 dark:text-white
bg-white dark:bg-white/5
border-gray-200 dark:border-white/10
text-gray-600 dark:text-gray-400
```

### Common Color Patterns

```blade
{{-- Headings --}}
text-zinc-900 dark:text-white

{{-- Descriptions --}}
text-zinc-600 dark:text-zinc-400
text-gray-600 dark:text-gray-400

{{-- Borders --}}
border-gray-200 dark:border-white/10

{{-- Backgrounds --}}
bg-white dark:bg-white/5
bg-gray-50 dark:bg-white/5
```

---

## Resolved Issues

### 1. Livewire MultipleRootElementsDetectedException

**Error:** `Livewire only supports one HTML element per component`
**Solution:** Livewire component views must have a single root element. All content should be wrapped in a `<div>`.

### 2. Alpine.js Store Undefined

**Error:** `$store.darkMode is not defined`
**Solution:** Alpine.js stores must be defined in the `alpine:init` event:

```javascript
document.addEventListener('alpine:init', () => {
    Alpine.store('darkMode', { ... });
});
```

### 3. Toggle Values Not Saving

**Error:** Form doesn't send value when checkbox is unchecked  
**Solution:** The `x-ui.toggle` component already includes a hidden input with value `0`; you only need the toggle. If you use a raw checkbox, add a hidden input before it:

```blade
<x-ui.toggle name="is_active" label="Active" :checked="old('is_active', true)" />
```

### 4. Quill Editor Content Not Visible in Visual Tab

**Error:** Content exists in HTML tab but not visible in Visual tab
**Solution:** Use `dangerouslyPasteHTML()` method:

```javascript
quill.clipboard.dangerouslyPasteHTML(0, htmlContent, "silent");
```

### 5. Blade and Alpine.js Syntax Conflict

**Error:** Error when using template literal (backtick)
**Solution:** Move complex JavaScript to a separate function:

```blade
<div x-data="myPageData()">
    ...
</div>

<script>
function myPageData() {
    return {
        // data and methods
    };
}
</script>
```

### 6. Alpine.js Component Data Access

**Error:** `__x.$data` undefined
**Solution:** In Alpine.js 3, use `_x_dataStack[0]`:

```javascript
const el = document.querySelector('[x-data="myData()"]');
if (el && el._x_dataStack && el._x_dataStack[0]) {
    el._x_dataStack[0].myMethod();
}
```

### 7. Table row toggle returns 403

**Error:** Clicking the is_active (or other toggle) column in the Livewire table returns 403.  
**Solution:** The table only allows toggling fields that are defined in `columns` with `'type' => 'toggle'`. Ensure you pass columns explicitly and include the toggle column, e.g.:

```php
:columns="[
    'id',
    'name',
    ['key' => 'is_active', 'type' => 'toggle'],
    // ...
]"
```

Pass columns with `:columns="$columns"` so the Livewire component receives the same config on AJAX requests. See [Table Usage](#table-usage) and `app\Livewire\Admin\Table.php` → `toggleField()`.

---

## Important Notes

### Component Paths

-   **UI components:** Use `x-ui.*` for all form and layout components: `x-ui.toggle`, `x-ui.modal`, `x-ui.drawer`, `x-ui.card`, `x-ui.input`, `x-ui.select`, `x-ui.textarea`, `x-ui.checkbox`, `x-ui.radio`, `x-ui.date-picker`, `x-ui.badge`, `x-button`, etc.
-   **Toggle:** Use **`x-ui.toggle`** (or alias `x-toggle`) only. The old `x-admin.inputs.toggle` / `x-admin.toggle` have been removed.
-   **Slug field:** Use **`x-ui.input`** with `slug-from="title"` (or the ID of the source field, e.g. `slug-from="name"`). No separate slug component.
-   **Image upload:** `x-image-upload` (or `x-ui.image-upload`). **Icon picker:** `x-icon-picker` (or `x-ui.icon-picker`). **Editor:** `x-editor`.
-   There are no remaining admin-specific input components; all use UI or shared components.

### Axios Usage

```javascript
// GET request
const { data } = await axios.get('/api/endpoint');

// POST request
axios.post('/api/endpoint', { key: 'value' })
    .then(response => { ... })
    .catch(error => { ... });
```

### Form Action Buttons

```blade
<div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-4 border-t border-gray-200 dark:border-white/10 pt-6">
    <a href="{{ route('...') }}" class="text-sm/6 font-semibold text-gray-900 dark:text-white hover:text-gray-600 dark:hover:text-gray-300">Cancel</a>
    <button type="submit" name="action" value="save" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90">
        <i class="fa-solid fa-check"></i>
        Save
    </button>
    <button type="submit" name="action" value="save_and_stay" class="inline-flex items-center gap-2 rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-gray-500">
        <i class="fa-solid fa-floppy-disk"></i>
        Save & Continue Editing
    </button>
</div>
```

### Save & Continue in Controller

```php
public function store(Request $request)
{
    $model = Model::create($validated);

    if ($request->input('action') === 'save_and_stay') {
        return redirect()->route('admin.model.edit', $model)
            ->with('success', 'Created successfully.');
    }

    return redirect()->route('admin.model.index')
        ->with('success', 'Created successfully.');
}
```

---

## File Structure

```
resources/views/admin/
├── content/
│   ├── blog/
│   │   ├── index.blade.php      # Uses Livewire Table
│   │   ├── create.blade.php     # 2/3-1/3 layout
│   │   ├── edit.blade.php       # 2/3-1/3 layout
│   │   ├── show.blade.php       # 2/3-1/3 layout
│   │   └── partials/            # Custom column views for table
│   ├── blog-category/
│   │   └── index.blade.php      # CRUD with Drawer
│   └── organization-name/
│       └── index.blade.php      # CRUD with Drawer
├── contact-forms/
│   ├── index.blade.php          # Manual table
│   └── show.blade.php           # 2/3-1/3 layout
└── index.blade.php              # Dashboard

resources/views/components/
├── ui/                          # UI components (use x-ui.* or registered aliases)
│   ├── toggle.blade.php         # x-ui.toggle / x-toggle
│   ├── modal.blade.php
│   ├── drawer.blade.php
│   ├── card.blade.php
│   ├── input.blade.php          # slug: use slug-from="title" or slug-from="name"
│   ├── select.blade.php
│   ├── textarea.blade.php
│   ├── checkbox.blade.php
│   ├── radio.blade.php
│   ├── date-picker.blade.php
│   └── badge.blade.php
├── admin/
│   └── inputs/                  # Empty; use x-ui.* and x-editor, x-icon-picker, x-image-upload
└── layouts/
    └── admin.blade.php          # Main layout component
```

---

## Example Pages

Well-designed pages that can be used as references:

1. **Blog Index:** `resources/views/admin/content/blog/index.blade.php`

    - Livewire Table usage
    - Social media sharing with Modal
    - Alpine.js integration

2. **Blog Create/Edit:** `resources/views/admin/content/blog/create.blade.php`

    - 2/3-1/3 grid layout
    - Quill Editor usage
    - Toggle usage
    - Image upload

3. **Blog Category Index:** `resources/views/admin/content/blog-category/index.blade.php`

    - CRUD operations with Drawer
    - Livewire Table

4. **Contact Forms:** `resources/views/admin/contact-forms/index.blade.php`
    - Manual table usage
    - Badges
    - Action buttons
