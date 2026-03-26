# Admin design system — compliance checklist

Canonical rules live in [cursor-admin-design-system.md](./cursor-admin-design-system.md). Use this file as a **PR-ready checklist** and a map to **existing Blade/Livewire building blocks**.

## Before you ship UI

- [ ] Reuse `resources/views/components/ui/*` before adding new primitives (`x-ui.button`, `x-ui.input`, `x-ui.modal`, `x-ui.slide-over`, etc.).
- [ ] No `alert()`, `confirm()`, or `prompt()` in admin flows; use toasts (`toastManager` / `notify` event) and `x-ui.modal` for **destructive** confirmation only.
- [ ] Non-destructive multi-step panels (social post, previews, settings) use **`x-ui.slide-over`** (or inline expansion), not centered modals.
- [ ] Prefer theme tokens and shared zinc/admin palette already used in `resources/css/admin.css` (`--color-accent`, etc.); avoid arbitrary `bg-[#...]` in new code.
- [ ] One `<h1>` per screen; page title uses `text-xl font-semibold` (not `text-3xl font-bold`) per the canonical doc.
- [ ] Terminology: **Articles** (not blogs/posts) in labels, placeholders, and toasts where the entity is a blog post.
- [ ] List pages: primary **Add** action in the **page header**; table toolbar = search + filters + row count + bulk actions (`livewire:admin.table`).
- [ ] Icon-only controls have `title` or `aria-label`.
- [ ] Z-index: overlays use `z-40` (backdrop) / `z-50` (panel); avoid `z-[9999]`.

## Component map (admin)

| Area | Use |
|------|-----|
| Layout | `resources/views/components/layouts/admin.blade.php` |
| Buttons | `x-ui.button` (`App\View\Components\UI\Button`) |
| Fields | `x-ui.input`, `x-ui.select`, `x-ui.textarea`, `x-ui.toggle`, `x-ui.checkbox` |
| Feedback | `x-ui.modal`, `x-ui.alert`, `x-ui.toast` + `resources/js/toast.js` |
| Slide-over | `x-ui.slide-over` (`resources/views/components/ui/slide-over.blade.php`) |
| Data tables | `livewire:admin.table` + `app/Livewire/Admin/Table.php` |
| Sidebar | `app/Livewire/Admin/Sidebar.php` + `resources/views/livewire/admin/sidebar.blade.php` |
| Breadcrumbs | `x-navigation.breadcrumbs` + `app/View/Components/Navigation/Breadcrumbs.php` |

## Related docs

- [cursor-admin-design-system.md](./cursor-admin-design-system.md) — full specification
- [OPCM_Design_Guide_Standard.md](./OPCM_Design_Guide_Standard.md) — OPCM reference (if present)
