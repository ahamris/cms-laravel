# OPCM Design Guide Standard

**Status:** Canonical  
**Version:** 2.0  
**Last updated:** 2026-03-26  
**Scope:** Admin UI (Pages, Articles, Taxonomies, System modules)

This file is the standardized Markdown design guide for OPCM.  
It is structured for practical implementation in Blade, Livewire, and Tailwind.

---

## 1) Purpose

- Create one shared UI/UX standard across all modules.
- Eliminate visual inconsistency and interaction drift.
- Provide clear implementation rules for humans and AI agents.

---

## 2) Core Principles

- Consistency over novelty.
- Information hierarchy first.
- Low-noise interfaces.
- Predictable action placement.
- Accessible and keyboard-friendly by default.

---

## 3) Stack and Constraints

| Layer | Standard |
|---|---|
| Styling | Tailwind CSS (semantic tokens only) |
| Components | Blade components |
| Reactivity | Livewire |
| Micro-interactions | Alpine.js |
| Icons | Heroicons / Font Awesome (consistent size usage) |
| Font | Inter |

Rules:

- No arbitrary hex values in templates.
- No inline style for color, spacing, and typography.
- No additional UI libraries without review.

---

## 4) Foundations

### 4.1 Breakpoints

- `xl` >= 1280
- `lg` >= 1024
- `md` >= 768
- `sm` >= 640
- base < 640

Desktop-first, mobile-safe behavior.

### 4.2 Typography

| Role | Standard |
|---|---|
| Page title | `text-xl font-semibold` |
| Section title | `text-base font-semibold` |
| Body text | `text-[12.5px]` |
| Help/meta | `text-[11px]` |
| Table header | `text-[10.5px] font-semibold uppercase tracking-wider` |

### 4.3 Spacing

Use consistent scale only: 4, 8, 12, 16, 20, 24, 32 px equivalents.

### 4.4 Radius

| Token | Usage |
|---|---|
| `rounded-sm` | chips, pills |
| `rounded` | inputs, buttons |
| `rounded-md` | alerts, compact cards |
| `rounded-lg` | cards, table wrappers, modals |
| `rounded-xl` | large overlays only |

---

## 5) Color System (Semantic)

Use semantic tokens defined in `tailwind.config.js`:

- `brand.*`
- `surface.*`
- `border.*`
- `text.*`
- `success.*`, `warning.*`, `danger.*`, `info.*`

Rules:

- Never use arbitrary classes like `bg-[#...]`.
- Use status colors only for status.
- Keep base surfaces neutral.

---

## 6) Layout Standards

### 6.1 App Shell

- Fixed left sidebar.
- Sticky topbar.
- Main content container with consistent paddings.

### 6.2 Header Pattern (all modules)

- Left: title + subtitle.
- Right: primary action (`Add ...`) + optional secondary actions.

### 6.3 Form Pattern

- Left column: main content fields.
- Right column: publishing/status/meta/media/settings.

---

## 7) Component Standards

### 7.1 Buttons

Variants:

- Primary: final/high-priority action.
- Secondary: support action (`Back`, `View`, `Cancel`).
- Ghost: low-emphasis only.
- Danger: destructive actions.

Global labels:

- `Save`
- `Save & close`
- `Cancel`
- `Back to list`
- `Delete`
- `Duplicate`

### 7.2 Forms

- Label above field.
- Helper text below field when needed.
- Inline validation under field.
- Required indicator consistent.
- Control heights consistent.

### 7.3 Cards

- Uniform card shell (neutral background, subtle border/shadow).
- Predictable header/body anatomy.
- Avoid deep card nesting.

### 7.4 Tables

All index pages should support:

- Search
- Relevant filters (status/type/category)
- Bulk selection and bulk delete
- Row actions (view/edit/delete + duplicate when needed)

Table behavior:

- Consistent header typography
- Uniform row height
- Right-aligned action cell
- Clear empty state

---

## 8) Interaction Standards

### 8.1 Overlay policy

- Modal: destructive confirmation only.
- Drawer: forms/settings/preview.
- Dropdown: lightweight contextual menus.

### 8.2 Feedback policy

- Use toast notifications for transient feedback.
- No browser `alert()` in production flows.
- Use inline validation for field errors.

### 8.3 Motion policy

- Subtle and fast transitions.
- No exaggerated transforms for admin actions.

---

## 9) Naming and Terminology

Canonical terms:

- `Articles`
- `Categories`
- `Types`
- `Tags`
- `Comments`

Use one term per concept across:

- Sidebar
- Header titles
- Breadcrumbs
- Buttons
- Empty states

---

## 10) Accessibility Baseline

- Visible focus style on all interactive controls.
- Icon-only buttons require `aria-label` or `title`.
- Keyboard support for all critical flows.
- Escape closes overlays.
- No hover-only critical info.
- Maintain WCAG AA contrast.

---

## 11) Delivery Workflow (Mandatory)

1. **Plan**
2. **Verify**
3. **Act**
4. **Test**
5. **Release**

Every UI task follows this order.

---

## 12) Module Completion Checklist

- [ ] Header and actions follow standard
- [ ] `Save` + `Save & close` present in forms
- [ ] Search/filter/bulk/actions present on index
- [ ] Empty and error states are polished
- [ ] Status/toggle patterns are consistent
- [ ] Naming uses canonical terms
- [ ] Accessibility checks pass

---

## 13) Tailwind Token Requirement

All custom colors/radius/font decisions must be defined in `tailwind.config.js` and consumed via semantic classes.

No direct hardcoded design values in Blade templates.

---

## 14) Governance

- Deviations require explicit PR justification.
- If a better pattern is adopted, update this guide first.
- This file is the implementation source of truth for contributors and AI tools.

