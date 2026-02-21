# Frontend API documentation

This document describes the **JSON API** for the headless CMS so a React SPA (or any frontend) can fetch content. **Content endpoints (pages, blog, legal, static, docs, live-sessions, modules, features, solutions, sitemap, vacancies, settings) are public** — no authentication is required. Access is restricted by **allowed origins** (Origin/Referer check). Configure `FRONTEND_ALLOWED_ORIGINS` in `.env`; if empty, all origins are allowed (suitable for development only).

**Base URL:** Use your app URL, e.g. `https://your-cms.test` or `https://cms.example.com`.  
Content endpoints live under `/api/`.  
Analytics endpoints live under `/api/analytics/` (see [Analytics](#analytics)) and are also public (rate-limited).

**Response format:** JSON.  
**Character encoding:** UTF-8.

---

## Table of contents

1. [Pages](#pages)
2. [Blog](#blog)
3. [Legal pages](#legal-pages)
4. [Static pages](#static-pages)
5. [Changelog](#changelog)
6. [Search](#search)
7. [Contact](#contact)
8. [Media and assets](#media-and-assets)
9. [Analytics](#analytics)
10. [Errors](#errors)
11. [CORS and security](#cors-and-security)

---

## Pages

CMS-managed pages (e.g. “Over ons”, “Prijzen”, custom landing pages).

### List pages

**GET** `/api/pages`  
**Auth:** None (public). Restricted by allowed origins (see [CORS and security](#cors-and-security)).

Returns a paginated list of **active** pages.

**Query parameters:**

| Parameter   | Type   | Default | Description                    |
|------------|--------|--------|--------------------------------|
| `per_page` | number | 12     | Items per page (1–100).        |
| `page`     | number | 1      | Page number (Laravel pagination). |

**Example request:**

```http
GET /api/pages?per_page=10&page=1
```

**Example response:** `200 OK`

```json
{
  "data": [
    {
      "id": 1,
      "title": "Over ons",
      "slug": "over-ons",
      "short_body": "Korte intro...",
      "meta_title": "Over ons | Site",
      "meta_body": null,
      "meta_keywords": null,
      "image": "https://your-cms.test/storage/pages/about.jpg",
      "icon": null,
      "url": "https://your-cms.test/pagina/over-ons",
      "created_at": "2025-01-15T10:00:00.000000Z",
      "updated_at": "2025-02-01T12:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 10,
    "total": 25,
    "from": 1,
    "to": 10
  }
}
```

List items do **not** include `long_body` (only the single-page response does).

---

### Get page by slug

**GET** `/api/pages/{slug}`  
**Auth:** None (public). Restricted by allowed origins (see [CORS and security](#cors-and-security)).

Returns a single **active** page by slug. Includes full `long_body` (HTML).

**Example request:**

```http
GET /api/pages/over-ons
```

**Example response:** `200 OK`

```json
{
  "data": {
    "id": 1,
    "title": "Over ons",
    "slug": "over-ons",
    "short_body": "Korte intro...",
    "long_body": "<p>Volledige HTML content...</p>",
    "meta_title": "Over ons | Site",
    "meta_body": null,
    "meta_keywords": null,
    "image": "https://your-cms.test/storage/pages/about.jpg",
    "icon": null,
    "url": "https://your-cms.test/pagina/over-ons",
    "created_at": "2025-01-15T10:00:00.000000Z",
    "updated_at": "2025-02-01T12:00:00.000000Z"
  }
}
```

**Error:** `404 Not Found` if no active page exists for the given slug.

---

## Blog

### Latest blog posts (preview)

**GET** `/api/blog-posts`  
**Auth:** None (public). Restricted by allowed origins (see [CORS and security](#cors-and-security)).

Returns the **latest 3** active blog posts. Useful for “latest articles” blocks on the homepage or in a page builder.

**Example request:**

```http
GET /api/blog-posts
```

**Example response:** `200 OK`

```json
[
  {
    "title": "Artikel titel",
    "slug": "artikel-titel",
    "url": "https://your-cms.test/artikelen/artikel-titel",
    "image": "https://your-cms.test/storage/blog/thumb.jpg",
    "short_body": "Eerste 160 tekens...",
    "date": "Feb 19, 2025",
    "date_attr": "2025-02-19",
    "category": "Nieuws",
    "category_slug": "nieuws",
    "author_name": "Jan Jansen",
    "author_avatar": "https://..."
  }
]
```

---

### Get blog post by slug

**GET** `/api/blog/{slug}`  
**Auth:** None (public). Restricted by allowed origins (see [CORS and security](#cors-and-security)).

Returns a single **active** blog post by slug, including full body and author/category.

**Example request:**

```http
GET /api/blog/artikel-titel
```

**Example response:** `200 OK`

```json
{
  "data": {
    "id": 5,
    "title": "Artikel titel",
    "slug": "artikel-titel",
    "short_body": "Korte samenvatting...",
    "long_body": "<p>Volledige HTML...</p>",
    "image": "https://your-cms.test/storage/blog/thumb.jpg",
    "meta_title": "Artikel titel | Site",
    "meta_description": "Omschrijving voor SEO",
    "url": "https://your-cms.test/artikelen/artikel-titel",
    "date": "Feb 19, 2025",
    "date_attr": "2025-02-19",
    "published_at": "2025-02-19T08:00:00.000000Z",
    "category": {
      "id": 2,
      "name": "Nieuws",
      "slug": "nieuws"
    },
    "author": {
      "id": 1,
      "name": "Jan Jansen",
      "avatar": "https://..."
    },
    "created_at": "2025-02-19T08:00:00.000000Z",
    "updated_at": "2025-02-19T09:00:00.000000Z"
  }
}
```

**Error:** `404 Not Found` if no active blog post exists for the slug.

---

## Legal pages

Legal/content pages (e.g. privacy, terms) with optional versioning.

### Get legal page by slug

**GET** `/api/legal/{slug}`  
**Auth:** None (public). Restricted by allowed origins (see [CORS and security](#cors-and-security)).

Returns a single **active** legal page by slug.

**Example request:**

```http
GET /api/legal/privacy
```

**Example response:** `200 OK`

```json
{
  "data": {
    "id": 1,
    "title": "Privacybeleid",
    "slug": "privacy",
    "body": "<p>Volledige HTML content...</p>",
    "meta_title": "Privacybeleid",
    "meta_description": "Beschrijving",
    "keywords": null,
    "image": "https://your-cms.test/storage/legal/og.jpg",
    "url": "https://your-cms.test/legal/privacy",
    "current_version": 2,
    "versioning_enabled": true,
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-02-01T00:00:00.000000Z"
  }
}
```

**Error:** `404 Not Found` if no active legal page exists for the slug.

---

## Static pages

Static/info pages managed in the CMS.

### Get static page by slug

**GET** `/api/static/{slug}`  
**Auth:** None (public). Restricted by allowed origins (see [CORS and security](#cors-and-security)).

Returns a single **active** static page by slug.

**Example request:**

```http
GET /api/static/faq
```

**Example response:** `200 OK`

```json
{
  "data": {
    "id": 1,
    "title": "Veelgestelde vragen",
    "slug": "faq",
    "body": "<p>HTML content...</p>",
    "meta_title": "FAQ",
    "meta_description": "Veelgestelde vragen",
    "keywords": null,
    "image": null,
    "url": "https://your-cms.test/static/faq",
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-02-01T00:00:00.000000Z"
  }
}
```

**Error:** `404 Not Found` if no active static page exists for the slug.

---

## Changelog

Changelog entries (product updates). Use the JSON API under `/api/changelog`.

### Changelog index

**GET** `/api/changelog`

**Auth:** None (public). Restricted by allowed origins.

Returns a list of **active** changelog entries (paginated). Query parameters: `per_page`, `page`.

**Example request:** `GET /api/changelog?per_page=10&page=1`

**Example response:** `200 OK` — structure follows the ChangelogController (data array + meta for pagination).

---

### Get changelog entry by slug

**GET** `/api/changelog/{slug}`

**Auth:** None (public). Restricted by allowed origins.

Returns a single **active** changelog entry by slug.

**Example request:** `GET /api/changelog/nieuwe-feature-x`

**Error:** `404 Not Found` if no active changelog exists for the slug.

---

## Search

### Search suggestions

**GET** `/api/search/suggestions`

Returns quick search suggestions (solutions, blog articles). Intended for autocomplete/typeahead.

**Query parameters:**

| Parameter | Type   | Required | Description        |
|----------|--------|----------|--------------------|
| `q`      | string | yes      | Search query (min. 2 characters). |

**Example request:**

```http
GET /api/search/suggestions?q=prijzen
```

**Example response:** `200 OK`

```json
{
  "suggestions": [
    {
      "title": "Prijzen",
      "type": "Oplossing",
      "url": "https://your-cms.test/oplossing/prijzen",
      "icon": "fa-briefcase"
    },
    {
      "title": "Prijzen en pakketten",
      "type": "Artikel",
      "url": "https://your-cms.test/artikelen/prijzen-en-pakketten",
      "icon": "fa-newspaper"
    }
  ],
  "mostSearched": ["term1", "term2"]
}
```

If `q` is shorter than 2 characters, `suggestions` is an empty array; `mostSearched` is still returned.

---

## Contact

Contact page data and form submission. All under `/api/contact`.

### Contact page data

**GET** `/api/contact`

**Auth:** None (public). Restricted by allowed origins.

Returns contact page content for the headless frontend (title, body, image, meta). If no "contact" page exists in the CMS, a fallback object is returned.

**Example request:** `GET /api/contact`

**Example response:** `200 OK` — `{"data": { "title": "...", "short_body": "...", "long_body": "...", "image": null, "image_url": "...", "meta_title": "...", "meta_body": "..." }}`

---

### Submit contact form

**POST** `/api/contact/verstuur`

Submit the main contact form. Expects **POST** with JSON or form body.

**Body (JSON or form):**  
Required: `first_name`, `last_name`, `email`, `phone`, `reden`, `bericht`, `avg-optin`, `contact_preference` (`"call"` or `"query"`).  
Optional: `company_name`, `country_code`.  
If `reden === 'ondersteuning'`, `bijlage` (file) is required (max 10MB; pdf, jpg, png, txt, doc, xls, ppt, etc.).

**Example response:** `201 Created`

```json
{
  "success": true,
  "message": "Bedankt! Uw reactie is geplaatst en wordt na controle gepubliceerd."
}
```

**Validation error:** `422 Unprocessable Entity`

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

---

## Media and assets

There is no dedicated media library table; images are embedded in content (pages, blog, settings). Use the fields `image`, `icon`, or `image_url` returned by pages, blog posts, and `GET /api/settings` for stable asset URLs (e.g. `https://your-cms.test/storage/...`).

### List media (placeholder)

**GET** `/api/media`

**Auth:** None (public). Restricted by allowed origins.

Returns a paginated list of media assets. Currently returns an empty list; this endpoint is reserved for a future media library. Query parameters: `per_page` (1–100, default 12), `page`.

**Example response:** `200 OK` — `{"data": [], "meta": {"current_page": 1, "last_page": 1, "per_page": 12, "total": 0, "from": null, "to": null}}`

---

## Analytics

Analytics endpoints are under **`/api/analytics/`** (see `routes/api.php`). They are rate-limited and public (no auth). Use them from the SPA to send page views and optional performance data.

### Track page view

**POST** `/api/analytics/track`

**Body (JSON):**

| Field        | Type   | Required | Description        |
|-------------|--------|----------|--------------------|
| `url`       | string | yes      | Page URL (max 500). |
| `page_title`| string | no       | Document title.     |
| `referrer`  | string | no       | Referrer URL.       |
| `user_agent`| string | no       | User agent.         |
| `metadata`  | object | no       | Extra key-value.    |

**Example response:** `200 OK`  
`{"status": "tracked"}` or `{"status": "skipped"}` or `{"status": "error"}`.

---

### Batch track (SPA)

**POST** `/api/analytics/batch-track`

**Body (JSON):**  
`views`: array of objects (max 10), each with at least `url`; optionally `page_title`, `referrer`.

**Example response:** `200 OK`  
`{"status": "ok", "tracked": 3}`.

---

### Guest activity

**POST** `/api/analytics/guest-activity`

**Body (JSON):**  
Fields as defined in `AnalyticsTrackingController@guestActivity` (e.g. activity type, URL, metadata).

---

### Performance

**POST** `/api/analytics/performance`

**Body (JSON):**  
Performance metrics (e.g. navigation timing, Core Web Vitals). Exact schema is defined in the controller.

---

### Stats

**GET** `/api/analytics/stats`

Returns aggregated stats (if implemented). Response shape depends on your app.

---

## Errors

- **403 Forbidden**  
  Returned when the request Origin/Referer is not in the allowed list (see [CORS and security](#cors-and-security)). Body: `{"message": "Origin not allowed."}`.

- **404 Not Found**  
  Returned when a single resource is requested by slug and no **active** item exists (e.g. `/api/pages/unknown`, `/api/blog/unknown`). Laravel may return HTML or JSON depending on `Accept` header; for SPA, send `Accept: application/json` to get JSON.

- **422 Unprocessable Entity**  
  Validation errors (e.g. contact form). Body includes `message` and `errors` (field → array of messages).

- **500 Internal Server Error**  
  Server or unexpected error. Contact/demo may return `success: false` and `message` in the body.

- **429 Too Many Requests**  
  Rate limiting (e.g. on analytics). Retry after the indicated period.

---

## CORS and security

- **Allowed origins:** Requests to content and analytics endpoints are checked against `FRONTEND_ALLOWED_ORIGINS` (comma-separated list in `.env`). If empty or not set, all origins are allowed. In production, set this to your SPA domain(s), e.g. `https://your-react-app.com`.
- **Content endpoints** (pages, blog, legal, static, docs, live-sessions, modules, features, solutions, sitemap, vacancies, settings) are **public** — no login or Bearer token. Security is via origin restriction only.
- Search suggestions (`/api/search/suggestions`), contact forms, and analytics are also public and rate-limited. Use HTTPS in production.

---

## Quick reference

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/pages` | — | List active pages (paginated) |
| GET | `/api/pages/{slug}` | — | Single page by slug |
| GET | `/api/blog-posts` | — | Latest 3 blog posts |
| GET | `/api/blog/{slug}` | — | Single blog post by slug |
| GET | `/api/legal/{slug}` | — | Single legal page by slug |
| GET | `/api/static/{slug}` | — | Single static page by slug |
| GET | `/api/settings` | — | Site + theme settings (homepage) |
| GET | `/api/docs` | — | Doc versions with sections/pages tree |
| GET | `/api/docs/search?q=` | — | Search documentation |
| GET | `/api/docs/{version}` | — | Single doc version with sections/pages |
| GET | `/api/docs/{version}/{section}/{page}` | — | Single doc page content |
| GET | `/api/live-sessions` | — | Upcoming + past live sessions |
| GET | `/api/live-sessions/{slug}` | — | Single live session |
| GET | `/api/modules` | — | List modules (with features) |
| GET | `/api/modules/{slug}` | — | Single module |
| GET | `/api/features` | — | List features |
| GET | `/api/features/{anchor}` | — | Single feature by anchor |
| GET | `/api/solutions` | — | List solutions |
| GET | `/api/solutions/{anchor}` | — | Single solution by anchor |
| GET | `/api/sitemap` | — | Sitemap as JSON (urls for SPA) |
| GET | `/api/media` | — | Media list (placeholder; empty for now) |
| GET | `/api/vacancies` | — | List vacancies (paginated, filterable) |
| GET | `/api/vacancies/{slug}` | — | Single vacancy |
| GET | `/api/changelog` | — | Changelog index (paginated) |
| GET | `/api/changelog/{slug}` | — | Single changelog entry by slug |
| GET | `/api/contact` | — | Contact page data |
| GET | `/api/search/suggestions?q=` | — | Search suggestions |
| POST | `/api/contact/verstuur` | — | Submit contact form |
| POST | `/api/analytics/track` | — | Track page view |
| POST | `/api/analytics/batch-track` | — | Batch track (SPA) |
| POST | `/api/analytics/guest-activity` | — | Guest activity |
| POST | `/api/analytics/performance` | — | Performance metrics |
| GET | `/api/analytics/stats` | — | Analytics stats |
