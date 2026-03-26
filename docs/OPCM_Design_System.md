# OPCM CMS — UI/UX Design System
**Version 1.0 · March 2026 · Tailwind + Livewire + Alpine.js**

This document is the single source of truth for every visual and interaction decision in the OPCM admin panel. All contributors — developers, designers, and AI assistants (Cursor / Claude) — must consult and apply these rules before writing any frontend code.

> **Any deviation from these rules must be documented in a PR comment with explicit justification. "It looked better" is not sufficient.**

---

## Table of Contents

1. [Foundations](#1-foundations)
2. [Layout System](#2-layout-system)
3. [Components](#3-components)
4. [Page Patterns](#4-page-patterns)
5. [Interaction Rules](#5-interaction-rules)
6. [Naming & Terminology](#6-naming--terminology)
7. [AI Assistant Instructions](#7-ai-assistant-instructions)
8. [Version History](#8-version-history)

---

## 1. Foundations

### 1.1 Tech Stack

The stack is fixed. No additional CSS frameworks or UI libraries may be added without architecture review.

| Layer | Technology |
|---|---|
| CSS framework | Tailwind CSS (Tailwind Plus / v3) |
| Component reactivity | Livewire 3 |
| Lightweight JS | Alpine.js v3 |
| Rich JS (editors, charts) | Vanilla JS / small targeted libs only |
| Icons | Heroicons SVG (inline) — 14×14px in nav/table/btn, 16×16px decorative |
| Fonts | Inter via Bunny Fonts CDN — no Google Fonts |

---

### 1.2 Breakpoints

OPCM is desktop-first. Mobile is a secondary PWA experience. Breakpoints are Tailwind defaults.

| Name | Min-width | Target | Notes |
|---|---|---|---|
| xl (native) | 1280px | 1920×1090 desktop | Full sidebar + content |
| lg | 1024px | Laptop 13" | Sidebar may collapse to icon-only |
| md | 768px | Tablet landscape | Sidebar hidden behind hamburger |
| sm | 640px | Tablet portrait / PWA | Single-column, bottom nav |
| (base) | < 640px | Mobile PWA | Full-width, stacked layout |

---

### 1.3 Color Palette

All colors are defined as Tailwind CSS variables in `tailwind.config.js`. **Never use arbitrary hex values in class names. Never hardcode colors in Blade or Livewire templates — always use semantic tokens.**

| Token | Hex | Usage | Tailwind class |
|---|---|---|---|
| `brand.DEFAULT` | `#4F46E5` | Primary actions, active states, links | `bg-brand text-brand` |
| `brand.hover` | `#4338CA` | Button hover, interactive states | `hover:bg-brand-hover` |
| `brand.light` | `#EEF2FF` | Active nav bg, tag fills | `bg-brand-light` |
| `brand.text` | `#3730A3` | Text on brand-light surfaces | `text-brand-text` |
| `surface.1` | `#FFFFFF` | Cards, panels, inputs | `bg-white` |
| `surface.2` | `#F8F8F9` | Page background, table header | `bg-surface-2` |
| `surface.3` | `#F1F0F5` | Hover rows, disabled fields | `bg-surface-3` |
| `border.DEFAULT` | `#E4E4E7` | All borders at 1px | `border-border` |
| `border.md` | `#D1D1D6` | Emphasis borders, focused inputs | `border-border-md` |
| `text.1` | `#18181B` | Headings, strong labels | `text-text-1` |
| `text.2` | `#52525B` | Body, form labels, nav items | `text-text-2` |
| `text.3` | `#A1A1AA` | Placeholders, hints, meta | `text-text-3` |
| `text.4` | `#D4D4D8` | Disabled text, icon fill fallback | `text-text-4` |
| `success.*` | bg `#F0FDF4` / text `#15803D` | Active status, positive badges | `bg-success-bg text-success-text` |
| `warning.*` | bg `#FFFBEB` / text `#B45309` | Caution states, alert strips | `bg-warning-bg text-warning-text` |
| `danger.*` | bg `#FEF2F2` / text `#DC2626` | Delete actions, error states | `bg-danger-bg text-danger-text` |
| `info.*` | bg `#EFF6FF` / text `#1D4ED8` | Informational strips | `bg-info-bg text-info-text` |

> **RULE:** Never use Tailwind arbitrary values like `bg-[#4F46E5]`. Define all custom colors in `tailwind.config.js` and use semantic class names only.

---

### 1.4 Typography

One font family. Two weights. Strict size scale. No exceptions.

| Role | Size | Weight | Line-height | Tailwind class |
|---|---|---|---|---|
| Page title (h1) | 20px | 600 | 28px | `text-xl font-semibold` |
| Section title (h2) | 16px | 600 | 24px | `text-base font-semibold` |
| Card title | 13px | 600 | 20px | `text-[13px] font-semibold` |
| Body / form labels | 12.5px | 400 / 500 | 20px | `text-[12.5px]` |
| Table cell text | 12.5px | 400 | 18px | `text-[12.5px]` |
| Table header | 10.5px | 600 | 14px | `text-[10.5px] font-semibold uppercase tracking-wider` |
| Section label (nav) | 10px | 600 | 14px | `text-[10px] font-semibold uppercase tracking-widest` |
| Meta / hint / help text | 11px | 400 | 16px | `text-[11px] text-text-3` |
| Badge / pill text | 11px | 500 | — | `text-[11px] font-medium` |
| Code / slug | 12px | 400 | 18px | `font-mono text-xs` |

> **RULE:** No font size below **10px** ever. No font size above **20px** in admin UI (page titles only). Font weight is either **400** (normal) or **500/600** (medium/semibold). **700 bold is never used in the admin.**

---

### 1.5 Spacing Scale

Use only these spacing values. No arbitrary pixel values in margin/padding utilities.

| Token | Value | Usage |
|---|---|---|
| 1 (4px) | `0.25rem` | Icon-label gap, micro spacing |
| 2 (8px) | `0.5rem` | Button internal gap, inline controls |
| 3 (12px) | `0.75rem` | Card padding tight, nav padding |
| 4 (16px) | `1rem` | Card padding standard, form row gap |
| 5 (20px) | `1.25rem` | Page header padding, section gap |
| 6 (24px) | `1.5rem` | Page body padding horizontal |
| 8 (32px) | `2rem` | Major section separation |

---

### 1.6 Border Radius

| Token | Value | Used for |
|---|---|---|
| `rounded-sm` | 4px | Badges, pills, small chips |
| `rounded` | 6px | Inputs, selects, buttons, action buttons |
| `rounded-md` | 8px | Stat card icons, upload zones, alert strips |
| `rounded-lg` | 10px | Cards, table wrappers, modals, drawers |
| `rounded-xl` | 12px | Overlay panels, feature callouts |
| `rounded-full` | 9999px | Avatar circles, toggle thumbs only |

> **RULE:** Sidebar has no border-radius. Topbar has no border-radius. Only cards, inputs, and buttons use rounded corners.

---

## 2. Layout System

### 2.1 App Shell — Desktop (≥ 1280px)

| Zone | Specification |
|---|---|
| Sidebar width | `220px` — fixed, never collapses on xl |
| Topbar height | `48px` — fixed, sticky |
| Content area | `flex-1 overflow-y-auto bg-surface-2` |
| Page header | `px-6 pt-5` — title + subtitle + primary action right |
| Page body | `px-6 pb-8` — all content lives here |
| Max content width | None — fills available space |
| Two-column form layout | `grid grid-cols-[1fr_268px] gap-3.5` |
| Four-column stat grid | `grid grid-cols-4 gap-2.5` |
| Two-column chart/widget | `grid grid-cols-[1.4fr_1fr] gap-3.5` |

---

### 2.2 Sidebar Structure

- **Logo zone** — 48px height, brand mark (24×24 `rounded-md`) + app name `text-[13px] font-semibold`, `border-b`
- **Nav groups** — each group separated by `border-t`. Section labels: `text-[10px] font-semibold uppercase tracking-widest text-text-3 px-3.5 pt-1.5 pb-0.5`
- **Nav items** — `text-[12.5px] py-1.5 px-3.5 gap-2 w-full transition-colors duration-100`
- **Active state** — `bg-brand-light text-brand-text font-medium` — set on the parent anchor, not a child element
- **Sub-navigation** — indented `pl-8` (32px), `text-[12px]`, same active rules
- **Badges in nav** — appended right: `bg-brand text-white text-[9px] font-semibold px-1.5 py-0.5 rounded-full`
- **Chevron** — right-aligned SVG 10×10, `opacity-40`, rotates 180° when sub-nav open (`transition-transform duration-200`)

---

### 2.3 Topbar Structure

- **Breadcrumb** — left side. Home icon (13×13) + separator `›` opacity-40 + current page label (`text-[12.5px] font-medium`). Max 2 levels.
- **Global search** — centered. `max-w-[360px] h-8 bg-surface-2 border-border rounded-md pl-8 pr-3 text-[12.5px]`. Left search icon 13×13 `text-text-3`. Placeholder: `Search anything… ⌘K`
- **Right zone** — dark-mode toggle icon-btn → notification bell icon-btn → avatar dropdown
- **Icon buttons** — `w-8 h-8 border border-border rounded-md bg-white hover:bg-surface-2`
- **Avatar dropdown** — `flex items-center gap-1.5 px-2 py-0.75 border border-border rounded-md`. 24×24 avatar circle. `text-[12px]` name text.

---

### 2.4 Mobile PWA Layout (< 640px)

| Zone | Mobile Specification |
|---|---|
| Sidebar | Hidden. Replaced by bottom nav bar (56px, max 5 icons) |
| Topbar | 48px. Logo left, search icon right, avatar right. No breadcrumb. |
| Bottom nav bar | `fixed bottom-0 w-full bg-white border-t h-14`. 5 icon+label items. 44×44px tap targets. |
| Active bottom nav | `text-brand`, icon filled variant |
| Page header | `px-4 pt-4`. Title `text-lg font-semibold`. Actions collapse into `⋯` overflow menu. |
| Page body | `px-4 pb-20` (extra bottom padding for bottom nav) |
| Stat grid | `grid-cols-2` on sm · `grid-cols-1` on base |
| Table | Horizontal scroll wrapper `overflow-x-auto -mx-4 px-4`. Hide low-priority columns (Featured, Social). |
| Form layout | Single column — right panel stacks below main content |
| Cards | Full width, `rounded-lg`, same padding (14px/16px) |
| Primary action | Full-width `w-full` in sticky bottom bar above bottom nav |
| Modals / Drawers | Drawers slide **up from bottom** (80vh). Modals full-screen on mobile. |
| Touch targets | Minimum **44×44px** for all interactive elements |
| Input height | **40px** on mobile (vs 34px desktop) for easier touch |
| Input font size | **16px** minimum on mobile — prevents iOS auto-zoom |

> **PWA RULE:** Never rely on hover states for critical functionality. Every interaction that shows information on hover on desktop must be accessible via tap on mobile.

---

## 3. Components

### 3.1 Buttons

Buttons have four variants. Each variant has one purpose. Never mix variants for the same action type across different screens.

| Variant | Class / Style | Usage | Rules |
|---|---|---|---|
| Primary | `bg-brand text-white border-brand` | Main CTA: Save, Publish, Add | Max 1 per action group. Never in tables. |
| Secondary | `bg-white border-border text-text-2` | Back, Cancel, View, Export | Multiple allowed. Right-aligned in header. |
| Ghost | `bg-transparent border-none text-text-2` | View all, inline toggle text | Low-emphasis only. Never for destructive. |
| Danger | `bg-danger-bg border-danger-border text-danger-text` | Delete, Remove, Revoke | Never adjacent to Primary. Spacing ≥ 8px. |

#### Button Sizes

| Size | Height | Padding | Use case |
|---|---|---|---|
| Default | 32px | `px-3` | Header actions, standalone CTAs |
| sm | 27px | `px-2.25` | Toolbar actions, inline card actions |
| xs | 23px | `px-1.75` | Table row secondary actions (rarely) |

**Rules:**
- **Icon in button** — always 13×13px SVG, `gap-1.5` with label. Never icon-only for primary/secondary (except icon-btn pattern).
- **Label convention** — verbs only: `Save` · `Publish` · `Delete` · `Edit` · `Duplicate` · `Back to [entity]` · `Add [entity]` · `View [entity]`
- **Loading state** — disable button + replace icon with 16×16 `animate-spin` spinner. Never change label to "Loading…".

---

### 3.2 Inputs & Form Fields

| Property | Desktop | Mobile |
|---|---|---|
| Height (text input) | 34px | 40px |
| Height (select) | 34px | 40px |
| Height (textarea) | auto, min 80px | auto, min 100px |
| Border | `1px solid border-border` | same |
| Border focused | `1px solid brand` + no ring | same |
| Border radius | `rounded` (6px) | same |
| Background | `bg-white` | same |
| Padding | `px-2.5` (10px) | `px-3` (12px) |
| Font size | 12.5px | **16px** (prevents iOS zoom) |

**Form anatomy rules:**
- **Form label** — `text-[11.5px] font-medium text-text-2 mb-1.25`. Required marker: `<span class="text-danger-text text-[10px]">*</span>` appended.
- **Help text** — `text-[11px] text-text-3 mt-0.75` below the field.
- **Error text** — `text-[11px] text-danger-text mt-0.75` below the field. Input gets `border-danger-border`.
- **Char counter** — right-aligned `text-[11px] text-text-3` in same flex row as label. Format: `0 / 150`.
- **Slug field** — prefix zone (`bg-surface-2 border-r px-2.5 text-text-3 text-[12px]`) + `input flex-1` joined in one container. No gap or separate border between prefix and input.
- **Form row spacing** — `mb-3.5` (14px) between rows. Last row `mb-0`.
- **Form sections** — separated by `<hr class="border-t border-border my-3.5">`. Never use headings inside a card body.

---

### 3.3 Cards

Cards are the primary container for grouped content. One structure, always.

- **Card wrapper** — `bg-white border border-border rounded-lg overflow-hidden`
- **Card header** — `px-4.5 py-3.5 border-b border-border`. Title `text-[13px] font-semibold`. Subtitle `text-[11.5px] text-text-3 mt-0.25`.
- **Card body** — `p-4.5` (18px). Override to `p-0` only when the card contains a table or full-bleed list.
- **Nesting** — max 1 level. A card inside a card is allowed only for stat-icon containers.
- **Stat card** — same wrapper. Contents: icon div (`32×32 rounded-md colored-bg`) → label (`11px/500 uppercase tracking`) → value (`24px/600`) → meta (`11px text-text-3`) → link (`11px text-brand`).
- **Hover on stat card** — `hover:border-brand`. Cursor pointer. No transform or shadow.

---

### 3.4 Tables (List Pages)

All list/index pages use a consistent table structure. No exceptions.

#### Toolbar (above table)

- **Search field** — left. `max-w-[280px] h-8`. Left search icon `12×12`. Placeholder: `Search [entity]s…`
- **Filters** — right of search. `h-8` select dropdowns. Max 3. `appearance-none` with custom chevron SVG.
- **Row count** — right side. `N articles` in `text-[11.5px] text-text-3`. Updates live on search/filter.
- **Bulk actions** — right side. Appears only when ≥1 checkbox checked. `btn-danger` "Delete selected".
- **Primary CTA** — in the **page header** (top-right), NOT in the toolbar.

#### Table Structure

- **Wrapper** — `bg-white border border-border rounded-lg overflow-hidden`
- **Header row** — `bg-surface-2`. `th`: `text-[10.5px] font-semibold uppercase tracking-wider text-text-3 py-2.25 px-3.5`
- **Data rows** — `py-2.75 px-3.5 border-b border-border`. Last row: no border. Hover: `bg-surface-2`.
- **Row height** — 44px minimum (accommodates toggles + thumbnail)
- **Checkbox column** — 36px wide. `input[type=checkbox]` `accent-brand` 14×14px.
- **Image column** — 44px wide. Thumbnail: `34×34 rounded-md bg-surface-2 border`. No image: SVG placeholder icon.
- **Title cell** — `font-medium text-text-1` (main line) + `text-[11px] text-text-3 mt-0.25` (sub-line e.g. email or slug).
- **Category/tag cell** — colored pill (see §3.6). Max-width auto.
- **Toggle columns** — `text-center`. Max 2 (Active, Featured).
- **Actions column** — `text-right w-[90px]`. Max 4 action-btn icons: view, edit, duplicate, delete.
- **Sort indicator** — `cursor-pointer` on th + sort-down SVG `9×9px` inline appended.

#### Action Buttons (in table rows)

- **Size** — `w-[27px] h-[27px] border border-border rounded bg-white`
- **Icon** — `12×12px` SVG inside
- **Hover: view/edit/duplicate** — `bg-surface-2`
- **Hover: delete** — `bg-danger-bg border-danger-border` — icon changes to `text-danger-text`
- **Gap** — `gap-0.75` (3px) between action buttons

---

### 3.5 Status Badges & Pills

| Variant | Background | Text color | When to use |
|---|---|---|---|
| Success / Active | `success-bg` (#F0FDF4) | `success-text` (#15803D) | Published, Active, Live |
| Warning / Draft | `warning-bg` (#FFFBEB) | `warning-text` (#B45309) | Draft, Pending, Scheduled |
| Danger / Inactive | `danger-bg` (#FEF2F2) | `danger-text` (#DC2626) | Inactive, Blocked, Error |
| Info | `info-bg` (#EFF6FF) | `info-text` (#1D4ED8) | Info messages, selected state labels |
| Neutral | `surface-3` (#F1F0F5) | `text-2` (#52525B) | Generic tags, non-semantic labels |
| Featured | `warning-bg` (#FFFBEB) | `warning-text` (#B45309) | Featured posts only. Star icon prepended. |

- **Pill anatomy** — `inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium`. No border unless "neutral".
- **Status dot** — for inline indicators: `7×7px` circle (`dot-green/amber/red/gray`) + `text-[12px] font-medium`. Gap `5px`.

---

### 3.6 Category Tags

Category tags use fixed color pairs. Do not add new pairs without updating this document and `tailwind.config.js`.

| Category | Background | Text | Tailwind token |
|---|---|---|---|
| Technologie & Innovatie | `#EFF6FF` | `#1D4ED8` | `cat-tech-bg` / `cat-tech-text` |
| Ondernemen | `#FFF7ED` | `#C2410C` | `cat-biz-bg` / `cat-biz-text` |
| Marketing Strategieën | `#FDF4FF` | `#7E22CE` | `cat-mkt-bg` / `cat-mkt-text` |
| Duurzaamheid | `#F0FDF4` | `#15803D` | `cat-dur-bg` / `cat-dur-text` |
| Persoonlijke Ontwikkeling | `#FFF1F2` | `#BE123C` | `cat-per-bg` / `cat-per-text` |

---

### 3.7 Toggle Switch

- **Size** — `32×18px` track. `14×14px` thumb. `top-0.5 left-0.5` unchecked → `translateX(14px)` checked.
- **Track unchecked** — `bg-border-md` (#D1D1D6). `transition-colors duration-200`.
- **Track checked** — `bg-brand` (#4F46E5).
- **Thumb** — `bg-white rounded-full shadow-sm`. `transition-transform duration-200`.
- **Implementation** — hidden `input[type=checkbox]` + sibling track div + sibling thumb div. Label wraps all three. Use `Alpine.js x-model` for Livewire binding.
- **In tables** — `text-center`. No label text. `title` attribute for accessibility.

```html
<label class="relative inline-block w-8 h-[18px] cursor-pointer">
  <input type="checkbox" class="sr-only peer" x-model="value">
  <div class="absolute inset-0 bg-border-md rounded-full transition-colors duration-200 peer-checked:bg-brand"></div>
  <div class="absolute top-0.5 left-0.5 w-3.5 h-3.5 bg-white rounded-full shadow-sm transition-transform duration-200 peer-checked:translate-x-3.5"></div>
</label>
```

---

### 3.8 Alert Strips

Alert strips appear at the top of a card body or panel. They are **not** modals or toasts.

- **Structure** — `flex items-center gap-2 px-3 py-2.25 rounded-md text-[12px] border`. Icon `13×13px flex-shrink-0`.
- **Warning strip** — `bg-warning-bg border-warning-border text-warning-text`
- **Info strip** — `bg-info-bg border-info-border text-info-text`
- **Position** — `mb-3.5` above the card it relates to, or inside card body above first field.
- **Links inside strips** — same color as strip text, `font-semibold underline`.

> **RULE:** Never use an alert strip as a persistent notification. For transient success/error, use the Toast system (§3.11).

---

### 3.9 Upload Zone

- **Structure** — `border-[1.5px] border-dashed border-border-md rounded-md p-5 text-center cursor-pointer`
- **Icon** — `24×24px` SVG `text-text-4 mb-1.5`
- **Hint text** — `text-[11.5px] text-text-3`
- **Spec text** — `text-[10.5px] text-text-4 mt-0.5`. Format: `JPEG, PNG, WebP · Max 20MB · Optimal 1200×630px`
- **Hover state** — `hover:border-brand hover:bg-brand-light`
- **Drag-active state** — `border-brand bg-brand-light ring-2 ring-brand ring-offset-1`

---

### 3.10 Rich Text Editor

- **Toolbar** — `flex flex-wrap items-center gap-0.25 p-1.5 bg-surface-2 border-b border-border`
- **Toolbar buttons** — `w-[26px] h-[26px] border-none rounded-sm hover:bg-surface-3`. Icon `12×12px`.
- **Toolbar separators** — `w-px h-[18px] bg-border mx-1`
- **Editor body** — `p-3 min-h-[160px] contenteditable text-[13px] text-text-2 leading-[1.7]`
- **Tab bar above editor** — "Editor | HTML". `text-[12.5px] font-medium`. Active: `text-brand border-b-2 border-brand`.

---

### 3.11 Toast Notifications

Toasts are the **only** way to show transient success, error, and informational feedback. No `alert()`. No in-form status messages for async actions.

- **Trigger** — Livewire event → Alpine.js reactive component
- **Position** — `fixed top-4 right-4 z-50`. Stacks downward.
- **Size** — `max-w-[320px] min-w-[240px]`
- **Anatomy** — `flex items-start gap-2.5 px-4 py-3 bg-white border rounded-lg shadow-md`. Icon `16×16`. Title `text-[12.5px] font-semibold`. Body `text-[12px] text-text-3` optional.
- **Variants** — Success `border-l-4 border-success-text` · Error `border-l-4 border-danger-text` · Info `border-l-4 border-brand` · Warning `border-l-4 border-warning-text`
- **Auto-dismiss** — 4000ms for success/info. 8000ms for error/warning. Close button always visible (×, 16×16).
- **Animation in** — `translate-x-full → translate-x-0 duration-200`
- **Animation out** — `opacity-100 → opacity-0 duration-150`

> **RULE:** Toasts are for feedback only. NEVER use a toast to show content that requires user action — use a Modal or Drawer for that.

---

### 3.12 Modals

Modals are for **confirmations only** — specifically destructive confirmations (delete, revoke, reset). All other overlays use the Slide-in Drawer (§3.13).

- **Backdrop** — `bg-black/40 fixed inset-0 z-40`
- **Panel** — `bg-white rounded-xl shadow-xl w-full max-w-[400px] mx-auto mt-[15vh] p-6 relative`
- **Title** — `text-[15px] font-semibold text-text-1`
- **Body** — `text-[13px] text-text-2 mt-2 mb-5`
- **Actions** — `flex justify-end gap-2`. Cancel (`btn-secondary`) + Confirm (`btn-danger` for delete, `btn-primary` for other).
- **Close on backdrop click** — always. Close on Escape key — always.
- **Animation** — `scale-95 opacity-0 → scale-100 opacity-100 duration-150 ease-out`

> **RULE:** Do NOT use modals for forms, settings panels, or any content longer than 2 sentences. Use the Drawer instead.

---

### 3.13 Slide-in Drawer (Right Panel)

Drawers replace modals for all editing, quick-preview, and settings contexts.

- **Desktop** — slides from right. Width: `max-w-[480px]` standard · `max-w-[640px]` complex forms.
- **Mobile** — slides from bottom. Height: `80vh rounded-t-xl`.
- **Backdrop** — `bg-black/30 fixed inset-0 z-40`. Click → close.
- **Panel** — `bg-white border-l border-border fixed top-0 right-0 h-full z-50 overflow-y-auto`
- **Header** — `h-12 px-5 border-b`. Title `text-[14px] font-semibold` + optional sub `text-[11.5px] text-text-3`. Close × button right-aligned.
- **Body** — `px-5 py-4 flex-1 overflow-y-auto`
- **Footer** — `h-14 sticky bottom-0 border-t bg-white px-5`. Save left (`btn-primary btn-sm`), Cancel right (`btn-ghost btn-sm`).
- **Animation open** — `translate-x-full → translate-x-0 duration-250 ease-out`
- **Animation close** — `translate-x-0 → translate-x-full duration-200 ease-in`

---

### 3.14 Pagination

- **Position** — below table. `flex items-center justify-between`
- **Left** — `Showing 1–N of M` in `text-[11.5px] text-text-3`
- **Page button** — `w-[30px] h-[30px] border border-border rounded bg-white text-[12px] text-text-2`
- **Active page** — `bg-brand text-white border-brand`
- **Prev/Next** — chevron SVG `10×10px`. Disabled: `opacity-40 cursor-not-allowed`
- **Visibility** — hide component entirely when total rows ≤ per-page limit

---

## 4. Page Patterns

### 4.1 Dashboard

- **Stat cards** — 4 columns on xl/lg · 2 on md · 1 on sm/base. Each: icon → label → value → meta → link.
- **Quick actions grid** — 3 columns. Each: icon `13×13` + label. `btn-secondary` with `hover:border-brand hover:bg-brand-light hover:text-brand`.
- **Two-column lower section** — left `1.4fr` (activity / chart), right `1fr` (status, reports).
- **Realtime indicator** — top-right of page header. Status dot (`dot-green`) + "Live" + large number + "Realtime visitors" label.
- **Section titles** — `text-[12px] font-semibold uppercase tracking-wider text-text-2`. Left-aligned. Small brand-color icon prepended.

---

### 4.2 List / Index Page

- **Header** — `page-title` + `page-sub`. Primary action (Add [entity]) `btn-primary` top-right.
- **Toolbar** — search left, filters, count right, bulk actions conditional right.
- **Table** — full width. Checkbox → Image → Title → [entity columns] → Toggles → Actions.
- **Pagination** — below table.
- **Empty state** — centered in table body. Icon `40×40 text-text-4` + `"No [entities] found" text-[14px] font-medium text-text-2` + optional `btn-primary` Add [entity].

---

### 4.3 Create / Edit Form Page

- **Layout** — `grid grid-cols-[1fr_268px] gap-3.5`. Main content left. Publishing/settings right.
- **Header** — `page-title` + `page-sub`. Back button (secondary) top-right. Save/Publish in sub-bar below header.
- **Save action bar** — `flex items-center gap-2 mb-4`. "Save draft" (`btn-secondary btn-sm`) + "Publish" (`btn-primary btn-sm`) + "All changes saved" (`text-[11.5px] text-text-3 italic ml-1`).
- **Left panel** — Post details card → Content card (rich editor). `gap-3.5` between cards.
- **Right panel** — Alert strip (if applicable) → Publishing card → Featured image card → SEO card. `gap-3` between cards.
- **Dirty state** — when unsaved changes exist: replace "All changes saved" with `"Unsaved changes" text-warning-text`. Add browser `beforeunload` warning.

---

### 4.4 View / Detail Page

- **Layout** — `grid grid-cols-[1fr_268px] gap-3.5`. Left: post details card. Right: publishing card, SEO card.
- **Header** — `page-title` + `page-sub`. Back → Edit → "View article" buttons top-right.
- **Section labels in view mode** — `text-[10.5px] font-semibold uppercase tracking-wider text-text-3 mb-1.5`. Display-only, not form labels.
- **Read-only values** — plain `text-[12.5px] text-text-1` OR styled container (code block for slug, image box for image, article-body prose for content).
- **Article body prose** — `text-[13.5px] text-text-1 leading-[1.75]`. `h2` inside content: `text-[15px] font-semibold`. Paragraphs: `mb-2.5`.
- **Slug display** — `font-mono text-[12px] bg-surface-2 px-2 py-1 rounded border block w-full`.

---

## 5. Interaction Rules

### 5.1 Overlays — Strict Rules

> **POPUP RULE:** The only UI element that should open as a centered overlay (modal) is a **DESTRUCTIVE CONFIRMATION** dialog. Everything else uses a Drawer (slides from right/bottom) or an inline Dropdown (anchored to trigger element).

| Allowed as | Use cases |
|---|---|
| **Modal** | Delete confirmation · Reset confirmation · Disconnect/Revoke confirmation |
| **Drawer** | Quick-edit form · Settings panel · Preview panel · Add-item panel · Import wizard |
| **Dropdown** | User menu · Filter options · Context menu · Kebab menu (⋯) in tables |
| **Never as any overlay** | Success messages (→ Toast) · Form validation errors (→ inline) · Status updates (→ Toast) |

---

### 5.2 Transitions & Animation

| Transition | Spec |
|---|---|
| Page transitions | None — Livewire handles navigation |
| Component mount | `opacity-0 → opacity-100 duration-150` |
| Drawer open | `translate-x-full → translate-x-0 duration-250 ease-out` |
| Drawer close | `translate-x-0 → translate-x-full duration-200 ease-in` |
| Modal open | `scale-95 opacity-0 → scale-100 opacity-100 duration-150 ease-out` |
| Toast in | `translate-x-full → translate-x-0 duration-200` |
| Toast out | `opacity-100 → opacity-0 duration-150` |
| Toggle | `background duration-200` + `transform duration-200` |
| Button hover | `background duration-[120ms]` — NO scale transforms |
| Table row hover | `background duration-100` |
| Nav item hover | `background duration-100 color duration-100` |

> **RULE:** Never use `duration > 300ms` for any UI transition in the admin. No bounce, spring, or elastic easing. `ease-out` for entering, `ease-in` for leaving.

---

### 5.3 Livewire-Specific Rules

- **Loading states** — use `wire:loading` directive. Disable buttons with `wire:loading.attr="disabled"`. Add spinner with `wire:loading.class="opacity-50"`.
- **Dirty detection** — use `$wire.entangle()` for two-way binding. Track form dirty state with Alpine.js `modified` flag.
- **Optimistic updates** — toggles (Active, Featured) update UI instantly, then sync with server. On server error: revert + show error toast.
- **Polling** — only for realtime visitor count on dashboard. `wire:poll.5000ms`. No polling elsewhere.
- **Flash messages** — `session()->flash()` → Livewire event → Alpine toast component. Never reload page for flash.

---

### 5.4 Keyboard & Accessibility

- **Focus ring** — all interactive elements: `focus-visible:ring-2 ring-brand ring-offset-1`. Never `outline-none` without a replacement.
- **Tab order** — logical DOM order. No `tabindex > 0`.
- **Escape key** — closes any open modal, drawer, or dropdown.
- **Enter key** — submits forms (not inside rich text editor). Activates focused buttons.
- **⌘K** — opens global search. Alpine.js `keydown.meta.k.window` listener.
- **ARIA labels** — all icon-only buttons must have `aria-label` or `title` attribute.
- **Color contrast** — all text must meet WCAG AA: 4.5:1 for body, 3:1 for large text.

---

## 6. Naming & Terminology

Consistent terminology prevents confusion between sidebar labels, page titles, breadcrumbs, and API references.

| Correct term | Never use | Context |
|---|---|---|
| Articles | Blogs, Posts, Blog posts | Sidebar label, page title, breadcrumb |
| Create article | Add blog, New post | Button label, page title |
| All articles | All blogs, Posts list | Sub-nav label |
| Categories | Blog categories (at top level) | Shared taxonomy. "Blog Categories" only inside Articles sub-nav |
| Active | Published, Live, Visible | Toggle label, status badge for published state |
| Draft | Unpublished, Hidden, Inactive | Status badge when not yet published |
| Featured | Pinned, Highlighted, Top | Featured toggle + badge |
| Slug | URL, Permalink, Path | Form label, display field |
| Back to [entity] | Go back, Return, ← Back | Button label. Always specify entity. |
| Delete | Remove, Destroy, Trash | Button label, modal title |
| Add [entity] | Create, New, + New | Primary CTA button in page header |
| Save draft | Save, Store, Keep | Secondary action on create/edit forms |
| Publish | Go live, Activate, Submit | Primary action on create/edit forms |
| Edit | Modify, Change, Update | Button label, page title |
| View | Preview, Show, Open | Button label |

---

## 7. AI Assistant Instructions

This section is specifically for **Cursor and Claude** when implementing UI changes. These rules must be followed before writing any frontend code.

### 7.1 Pre-Implementation Checklist

Before writing any component, Blade template, or Livewire view, verify:

- [ ] Is there an existing component that handles this UI pattern? (Check `/resources/views/components/`)
- [ ] Does the design match the page pattern in §4 exactly? If not, document why in a code comment.
- [ ] Are all colors using the semantic Tailwind tokens — not arbitrary hex values?
- [ ] Are all font sizes on the approved scale (§1.4)?
- [ ] Is the button variant correct for its function (§3.1)?
- [ ] Is the interaction handled by the correct overlay type (§5.1)?
- [ ] Does the component work at all 5 breakpoints (§2.4)?

---

### 7.2 Strict Do / Don't Rules

| ❌ NEVER do this | ✅ ALWAYS do this instead |
|---|---|
| `class="text-[#4F46E5]"` | `class="text-brand"` |
| `style="font-size: 13px"` | `class="text-[13px]"` |
| `alert("Saved!")` | Dispatch Livewire toast event |
| `<div class="modal">` for a form | Use Drawer component |
| `<div class="modal">` for delete confirm | Use Modal component (only allowed modal) |
| `bg-green-500` for success | `bg-success-bg text-success-text` |
| `font-bold` (weight 700) | `font-semibold` (600) or `font-medium` (500) |
| `<p>` with inline styles | Typography classes from §1.4 |
| `rounded-full` on a card | `rounded-lg` on cards always |
| Arbitrary `z-index: 9999` | `z-50` (drawer) · `z-40` (backdrop) · `z-30` (dropdown) |
| New color directly in template | Add token to `tailwind.config.js` first, then use class |
| Import a new UI library without review | Use existing Alpine.js + Tailwind patterns |
| `hover:` state as the only way to see info | Ensure info accessible via tap on mobile |
| Page-level `<h1>` appearing more than once | One `<h1>` per page, always |
| Closing a modal on form submission | Use Toast for feedback, keep drawer open on error |

---

### 7.3 Component Checklist Before PR

- [ ] Page title is `<h1 class="text-xl font-semibold">` — only one per page
- [ ] All form labels have correct `for`/`id` pairing
- [ ] All icon-only buttons have `aria-label` or `title`
- [ ] All toggles have `title` attribute explaining the action
- [ ] Mobile layout tested at 390px width (iPhone viewport)
- [ ] Keyboard navigation tested: Tab, Shift+Tab, Enter, Escape
- [ ] No hardcoded colors, sizes, or spacing outside the design system
- [ ] No `z-index` values other than `z-30`, `z-40`, `z-50`
- [ ] Livewire loading states added to all form submissions (`wire:loading`)
- [ ] Empty state exists for all tables and lists
- [ ] Error state exists for all async operations (toast on failure)

---

### 7.4 Tailwind Config — Full Token Block

Paste this into the `theme.extend` section of `tailwind.config.js`:

```js
// tailwind.config.js — theme.extend
colors: {
  brand: {
    DEFAULT: '#4F46E5',
    hover:   '#4338CA',
    light:   '#EEF2FF',
    text:    '#3730A3',
  },
  surface: {
    1: '#FFFFFF',
    2: '#F8F8F9',
    3: '#F1F0F5',
  },
  border: {
    DEFAULT: '#E4E4E7',
    md:      '#D1D1D6',
  },
  text: {
    1: '#18181B',
    2: '#52525B',
    3: '#A1A1AA',
    4: '#D4D4D8',
  },
  success: {
    bg:     '#F0FDF4',
    text:   '#15803D',
    border: '#BBF7D0',
  },
  warning: {
    bg:     '#FFFBEB',
    text:   '#B45309',
    border: '#FDE68A',
  },
  danger: {
    bg:     '#FEF2F2',
    text:   '#DC2626',
    border: '#FECACA',
  },
  info: {
    bg:     '#EFF6FF',
    text:   '#1D4ED8',
    border: '#BFDBFE',
  },
  // Category tag pairs
  'cat-tech': { bg: '#EFF6FF', text: '#1D4ED8' },
  'cat-biz':  { bg: '#FFF7ED', text: '#C2410C' },
  'cat-mkt':  { bg: '#FDF4FF', text: '#7E22CE' },
  'cat-dur':  { bg: '#F0FDF4', text: '#15803D' },
  'cat-per':  { bg: '#FFF1F2', text: '#BE123C' },
},
borderRadius: {
  sm:  '4px',
  DEFAULT: '6px',
  md:  '8px',
  lg:  '10px',
  xl:  '12px',
},
fontFamily: {
  sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
},
```

---

## 8. Version History

| Version | Date | Author | Changes |
|---|---|---|---|
| 1.0 | 2026-03-26 | OPCM Team | Initial release. All 8 sections. 4 screens documented. |

---

*— End of OPCM UI/UX Design System v1.0 —*
