# Headless CMS — Project Analysis

This document describes what the project is, what it does, and how its main parts fit together.

---

## 1. What the project is

**headless-cms** is a Laravel 12 application that acts as:

1. **Headless CMS API** — A versioned REST API (v1) for a React (or other) SPA: authentication via Laravel Sanctum, and a minimal content API (pages).
2. **Built-in admin panel** — A Laravel-based admin UI (Livewire, Blade) for managing users, roles, permissions, settings, menu, and theme. The frontend SPA is separate; only the admin is served by this app.
3. **API documentation** — OpenAPI/Swagger docs generated with L5-Swagger, exposed at `/api/documentation`.

So: one Laravel app = API for the SPA + admin panel + Swagger.

---

## 2. Tech stack (summary)

| Layer | Technology |
|-------|------------|
| Framework | Laravel 12, PHP ^8.4 |
| API auth | Laravel Sanctum (session + token) |
| API docs | darkaonline/l5-swagger (OpenAPI 3) |
| Admin UI | Livewire 4, Blade, Tailwind-style components |
| Roles/permissions | Spatie Laravel Permission (used in seeders/controllers) |
| Auth (web) | Laravel Fortify (implied by Fortify actions in `app/Actions/Fortify`) |
| Testing | Pest 4 |
| Tooling | Laravel Pint, Laravel Pail, Boost, Sail (dev) |

---

## 3. What the project does

### 3.1 Headless CMS API (`/api/v1/*`)

- **Authentication**
  - **POST /api/v1/login** — Log in with `email` + `password`. Optional body field `token: true` returns a Bearer token for cross-origin clients; otherwise session/cookie for same-origin.
  - **POST /api/v1/logout** — Log out (revoke token or invalidate session). Requires `auth:sanctum`.
  - **GET /api/v1/user** — Current authenticated user. Requires `auth:sanctum`.

- **Content (public, no auth)**
  - **GET /api/v1/pages** — Paginated list of *published* pages (`per_page` query param).
  - **GET /api/v1/pages/{slug}** — Single published page by slug; 404 if not found or not published.

- **Auth model** — `User` uses `HasApiTokens` (Sanctum). API guard is `sanctum` in `config/auth.php`. Stateful domains (e.g. `headless-cms.test`, localhost) are in `config/sanctum.php`. CORS is configured in `config/cors.php` with `supports_credentials` for SPA.

- **Content model** — `Page`: `title`, `slug` (unique), `body`, `published_at`. Scope `published()` filters by `published_at <= now()`. API responses use `PageResource` and `UserResource`.

- **Public content security (no login)** — Content (pages) is public. Security between React and the CMS is via: **CORS** (restrict origins with `CORS_ALLOWED_ORIGINS`), **optional API key** for content routes (`CMS_API_KEY` + `X-API-Key` or `Authorization: Bearer`), **rate limiting** (60/min), and **HTTPS** in production. See [security.md](security.md).

Result: the React SPA can read published pages without logging in; optional API key and CORS restrict who can call the API. Sanctum (login/logout/user) remains for admin or future protected endpoints.

### 3.2 Admin panel (web, `/admin/*`)

- **Entry** — `/` redirects to `/admin`. Admin routes are under `Route::prefix('admin')->middleware(['auth'])`.

- **Features**
  - **Dashboard** — Home and analytics (AdminController).
  - **Users** — CRUD (`users` resource), with roles (e.g. admin, editor, user) and role badges.
  - **Roles** — CRUD (`roles` resource).
  - **Permissions** — CRUD (`permissions` resource); permissions are mapped to roles via `Variable::$fullPermissions` and seeders.
  - **Settings** — General, menu (Livewire MenuManager), theme (Livewire ThemeManager).
  - **Profile** — Edit profile and update password (Fortify-style actions).
  - **2FA** — Two-factor recovery route and Livewire two-factor settings.

- **UI** — Livewire components, Blade layouts (`admin`, `auth`), reusable UI components (buttons, modals, inputs, etc.) and documentation views for them. Styling via `resources/css/admin.css` and Tailwind-style component classes.

- **Auth** — Web guard, session-based. Default accounts and roles come from `Variable::DEFAULT_ACCOUNTS` and `Variable::$fullRoles` (AdminSeeder, PermissionSeeder).

Result: admins manage users, roles, permissions, menu, and theme in this Laravel app. Content (e.g. pages) can be managed here or via future admin-only API endpoints.

### 3.3 Swagger / OpenAPI

- **Package** — `darkaonline/l5-swagger`. Base spec in `app/OpenApi.php` (info, server, security schemes: Bearer token + sanctum cookie).
- **Controllers** — Auth and Page controllers are annotated with OpenAPI attributes (e.g. `#[OA\Post(...)]`, `#[OA\Get(...)]`). `UserResource` and `PageResource` define schemas.
- **Generation** — `php artisan l5-swagger:generate` writes JSON/YAML to `storage/api-docs/`.
- **UI** — Swagger UI is served at **/api/documentation** (package default).

Result: API consumers and developers can read and try the headless CMS API from the browser.

---

## 4. Project structure (high level)

```
├── app/
│   ├── OpenApi.php                 # Base OpenAPI info & security schemes
│   ├── Actions/Fortify/            # Profile/password/registration (admin auth)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/V1/             # Headless CMS API
│   │   │   │   ├── Auth/           # Login, Logout, User
│   │   │   │   └── PageController.php
│   │   │   └── Admin/              # Admin panel (dashboard, users, roles, etc.)
│   │   ├── Requests/Api/           # e.g. LoginRequest
│   │   └── Resources/              # UserResource, PageResource
│   ├── Livewire/Admin/             # Menu, theme, table, search, sidebar, 2FA
│   ├── Models/                     # User (HasApiTokens), Page; Admin/* for menu/theme
│   ├── Helpers/                    # Variable (roles, permissions, defaults), Theme, Flash
│   └── View/Components/            # UI and layout components
├── routes/
│   ├── api.php                     # /api/v1/* (auth + pages)
│   └── web.php                     # /admin/* (admin panel), redirect /
├── config/
│   ├── auth.php                    # web + api (sanctum) guards
│   ├── sanctum.php                 # stateful domains
│   └── cors.php                    # API + credentials
├── database/
│   ├── migrations/                 # users, pages, personal_access_tokens, roles/permissions, etc.
│   ├── seeders/                    # AdminSeeder, PermissionSeeder, AdminMenuSeeder
│   └── factories/                  # User, Page
└── resources/
    ├── docs/                       # This analysis
    ├── views/                      # admin/*, livewire/*, components/*, errors/*
    └── css/admin.css               # Admin panel styles
```

---

## 5. Data and configuration

- **Roles** — Defined in `Variable::$fullRoles` (e.g. admin, editor, user). Created by AdminSeeder.
- **Permissions** — Defined in `Variable::$fullPermissions` (site_setting, user_*, permission_*, role_*, media_*, etc.) and assigned to roles by PermissionSeeder.
- **Default admin** — From `Variable::DEFAULT_ACCOUNTS` (e.g. admin@example.com / password), created by AdminSeeder.
- **API versioning** — All API routes under `api/v1`; allows future `api/v2` without breaking the SPA.

---

## 6. Results (what you get)

| Deliverable | Result |
|------------|--------|
| **SPA backend** | REST API with login (session or token), logout, current user, and read-only published pages. |
| **Admin panel** | Full in-app UI for users, roles, permissions, settings, menu, theme, profile, 2FA. |
| **Swagger** | OpenAPI 3 spec and Swagger UI at `/api/documentation` for the headless CMS API. |
| **Content model** | `Page` with publish workflow; public endpoints for listing and fetching by slug. |

The React SPA uses the API for auth and content; admins use the Laravel admin for user and system management. No React code lives in this repo; it is a backend + admin only.
