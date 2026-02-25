# Frontend API documentation

This document describes the **JSON API** for the headless CMS so a React SPA (or any frontend) can fetch content. **Content endpoints are public** — no authentication is required. Access is restricted by **allowed origins** (Origin/Referer check). Configure `FRONTEND_ALLOWED_ORIGINS` in `.env`; if empty, all origins are allowed (suitable for development only).

**Base URL:** Use your app URL, e.g. `https://your-cms.test` or `https://cms.example.com`.  
Content endpoints live under `/api/`.  
Analytics endpoints live under `/api/analytics/` (see [Analytics](#analytics)); they are public and rate-limited.

**Response format:** JSON (unless noted).  
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
8. [Homepage content](#homepage-content)
9. [Settings](#settings)
10. [Menus](#menus)
11. [Documentation](#documentation)
12. [Course / Academy](#course--academy)
13. [Pricing](#pricing)
14. [Trial](#trial)
15. [Vacancies](#vacancies)
16. [Sitemap and robots](#sitemap-and-robots)
17. [Media](#media)
18. [Analytics](#analytics)
19. [Errors](#errors)
20. [CORS and security](#cors-and-security)
21. [Quick reference](#quick-reference)

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
**Auth:** None (public). Restricted by allowed origins.

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

### List blog posts

**GET** `/api/blog`  
**Auth:** None (public). Restricted by allowed origins.

Returns a paginated list of **active** blog posts. When not filtering by search or category, the first “featured” article (if any) is excluded from the main list; the response still uses cursor-style pagination with `has_more` and `next_page`.

**Query parameters:**

| Parameter   | Type   | Default | Description                              |
|------------|--------|--------|------------------------------------------|
| `per_page` | number | 6      | Items per page (1–24).                   |
| `page`     | number | 1      | Page number.                             |
| `search`   | string | —      | Filter by title/body/keywords.            |
| `category` | string | —      | Filter by category slug.                  |

**Example request:**

```http
GET /api/blog?per_page=6&page=1
GET /api/blog?search=prijzen&category=nieuws
```

**Example response:** `200 OK`

```json
{
  "data": [
    {
      "title": "Artikel titel",
      "slug": "artikel-titel",
      "url": "https://your-cms.test/api/blog/artikel-titel",
      "image": "https://your-cms.test/storage/blog/thumb.jpg",
      "short_body": "Eerste 160 tekens...",
      "date": "Feb 19, 2025",
      "date_attr": "2025-02-19",
      "category": "Nieuws",
      "category_slug": "nieuws",
      "author_name": "Jan Jansen",
      "author_avatar": "https://..."
    }
  ],
  "has_more": true,
  "next_page": 2
}
```

---

### Get blog post by slug

**GET** `/api/blog/{slug}`  
**Auth:** None (public). Restricted by allowed origins.

Returns a single **active** blog post by slug, including full body, author, category, and approved comments (with replies).

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
    "url": "https://your-cms.test/api/blog/artikel-titel",
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
    "comments": [],
    "created_at": "2025-02-19T08:00:00.000000Z",
    "updated_at": "2025-02-19T09:00:00.000000Z"
  }
}
```

**Error:** `404 Not Found` if no active blog post exists for the slug.

---

### Post comment

**POST** `/api/blog/{slug}/comments`  
**Auth:** None (public). Rate-limited (`throttle:forms`).

Submit a comment (or reply) on a blog post. Send as **form data** (application/x-www-form-urlencoded or multipart/form-data).

**Body (form):**

| Field       | Type    | Required | Description                              |
|------------|---------|----------|------------------------------------------|
| `body`     | string  | yes      | Comment text (max 2000).                 |
| `parent_id`| integer | no       | Parent comment ID for replies.           |
| `guest_name`  | string | yes*     | Name (required when not authenticated). |
| `guest_email` | string | yes*     | Email (required when not authenticated). |
| `hp_phone` | string  | no       | Honeypot; leave empty.                   |

*When the user is not logged in, `guest_name` and `guest_email` are required.

**Example response:** `201 Created`

```json
{
  "success": true,
  "message": "Bedankt! Uw reactie is geplaatst en wordt na controle gepubliceerd."
}
```

**Errors:** `404` (post not found), `422` (validation failed).

---

### Like / dislike comment

**POST** `/api/blog/{slug}/comments/{comment}/like`  
**POST** `/api/blog/{slug}/comments/{comment}/dislike`  

**Auth:** None (public).

Record a like or dislike on a comment. Response shape is implementation-specific; typically `200 OK` with a status or updated counts.

---

## Legal pages

Legal/content pages (e.g. privacy, terms) with optional versioning.

### Get legal page by slug

**GET** `/api/legal/{slug}`  
**Auth:** None (public). Restricted by allowed origins.

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
**Auth:** None (public). Restricted by allowed origins.

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

Changelog entries (product updates).

### Changelog index

**GET** `/api/changelog`  
**Auth:** None (public). Restricted by allowed origins.

Returns a paginated list of **active** changelog entries.

**Query parameters:**

| Parameter   | Type   | Default | Description                                    |
|------------|--------|--------|------------------------------------------------|
| `per_page` | number | 10     | Items per page (1–50).                         |
| `status`   | string | api    | `api` = API changelog only; `all` = all statuses. |

**Example request:**

```http
GET /api/changelog?per_page=10&page=1&status=api
```

**Example response:** `200 OK`

```json
{
  "data": [
    {
      "id": 1,
      "title": "Nieuwe feature X",
      "slug": "nieuwe-feature-x",
      "description": "...",
      "content": "...",
      "date": "2025-02-01",
      "status": "api",
      "is_active": true,
      "created_at": "...",
      "updated_at": "..."
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 5
  }
}
```

---

### Get changelog entry by slug

**GET** `/api/changelog/{slug}`  
**Auth:** None (public). Restricted by allowed origins.

Returns a single **active** changelog entry by slug.

**Example request:** `GET /api/changelog/nieuwe-feature-x`

**Error:** `404 Not Found` if no active changelog exists for the slug.

---

## Search

### Full-text search

**GET** `/api/search`  
**Auth:** None (public). Restricted by allowed origins.

Search across pages, blog, solutions, docs, course (videos/categories), and changelog. Results are paginated and typed.

**Query parameters:**

| Parameter   | Type   | Required | Default | Description                                                                 |
|------------|--------|----------|--------|-----------------------------------------------------------------------------|
| `q`        | string | yes      | —      | Search query (min. 2 characters).                                           |
| `type`     | string | no       | all   | Scope: `all`, `pages`, `blog`, `solutions`, `docs`, `course`, `changelog`.  |
| `per_page` | number | no       | 15    | Results per page (1–50).                                                    |
| `page`     | number | no       | 1     | Page number.                                                               |

**Example request:**

```http
GET /api/search?q=prijzen&type=blog&per_page=10&page=1
```

**Example response:** `200 OK`

```json
{
  "data": [
    {
      "type": "blog",
      "title": "Prijzen en pakketten",
      "excerpt": "Eerste 160 tekens...",
      "url": "https://your-cms.test/api/blog/prijzen-en-pakketten",
      "slug": "prijzen-en-pakketten"
    },
    {
      "type": "page",
      "title": "Prijzen",
      "excerpt": "...",
      "url": "https://your-cms.test/api/pages/prijzen",
      "slug": "prijzen"
    }
  ],
  "meta": {
    "query": "prijzen",
    "type": "blog",
    "total": 12,
    "current_page": 1,
    "last_page": 2,
    "per_page": 10
  }
}
```

If `q` is shorter than 2 characters, `data` is an empty array and `meta.total` is 0.

---

### Search suggestions

**GET** `/api/search/suggestions`  
**Auth:** None (public). Restricted by allowed origins.

Returns quick search suggestions (solutions, blog articles) for autocomplete/typeahead, plus “most searched” terms.

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
      "url": "https://your-cms.test/api/solutions/prijzen",
      "icon": "fa-briefcase"
    },
    {
      "title": "Prijzen en pakketten",
      "type": "Artikel",
      "url": "https://your-cms.test/api/blog/prijzen-en-pakketten",
      "icon": "fa-newspaper"
    }
  ],
  "mostSearched": [
    { "term": "Boekhouden", "icon": "fa-bookmark", "url": "https://your-cms.test/api/solutions" },
    { "term": "BTW-aangifte", "icon": "fa-calculator", "url": "https://your-cms.test/api/solutions" }
  ]
}
```

If `q` is shorter than 2 characters, `suggestions` is an empty array; `mostSearched` is still returned.

---

## Contact

Contact page data and form submission.

### Contact page data

**GET** `/api/contact`  
**Auth:** None (public). Restricted by allowed origins.

Returns contact page content for the headless frontend (title, body, image, meta). If no “contact” page exists in the CMS, a fallback object is returned.

**Example request:** `GET /api/contact`

**Example response:** `200 OK`

```json
{
  "data": {
    "title": "Hulp nodig bij de uitvoering van de Wet open overheid?",
    "short_body": "Laat hier je gegevens achter...",
    "long_body": "<p>...</p>",
    "image": null,
    "image_url": "https://...",
    "meta_title": "...",
    "meta_body": "..."
  }
}
```

---

### Submit contact form

**POST** `/api/contact/verstuur`  
**Auth:** None (public). Rate-limited (`throttle:forms`).

Submit the main contact form. Send as **JSON** or **form data** (application/x-www-form-urlencoded or multipart/form-data for file attachment).

**Body (JSON or form):**

| Field               | Type   | Required | Description                                      |
|--------------------|--------|----------|--------------------------------------------------|
| `first_name` / `voornaam`   | string | yes | First name.                                      |
| `last_name` / `achternaam`  | string | yes | Last name.                                       |
| `email`            | string | yes      | Email.                                            |
| `phone`            | string | no       | Phone.                                            |
| `reden` / `onderwerp` | string | yes | Subject/reason.                                |
| `company_name` / `organisatie` | string | no | Company.                                    |
| `bericht`          | string | yes      | Message.                                          |
| `avg-optin`        | —      | yes      | Privacy consent (1 or true when agreed).          |
| `contact_preference` | string | yes   | `"call"` or `"query"`.                           |
| `country_code`     | string | no       | Optional.                                         |
| `bijlage`          | file   | conditional | Required when `reden === 'ondersteuning'`. Max 10MB; pdf, jpg, png, txt, doc, docx, xls, xlsx, ppt, pptx. |

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

## Homepage content

Editable homepage sections for the SPA (hero, feature cards, about, how it works, user features, competition, latest updates title, bottom CTA). **Header and footer** are not part of this endpoint; use **GET /api/settings** and **GET /api/menus** for site/theme and menu data.

### Get homepage sections

**GET** `/api/homepage`  
**Auth:** None (public). Restricted by allowed origins.

Returns an object whose keys are section identifiers and values are the section content. Stored image paths are returned as full URLs.

**Section keys:** `hero`, `feature_cards`, `about_opms`, `how_it_works`, `user_features`, `competition`, `latest_updates`, `bottom_cta` (and any others configured in the CMS).

**Example request:** `GET /api/homepage`

**Example response:** `200 OK`

```json
{
  "hero": {
    "label": "OPMS OPEN PUBLICATION PLATFORM",
    "heading": "Grip op informatie van bron tot burger",
    "paragraph": "...",
    "bullets": [
      { "icon": "check", "text": "Bullet 1" },
      { "icon": "check", "text": "Bullet 2" }
    ],
    "cta_primary_text": "Demo aanvragen",
    "cta_primary_url": "/demo",
    "cta_secondary_text": "Meer informatie",
    "cta_secondary_url": "/info",
    "image": "https://your-cms.test/storage/homepage/hero.jpg"
  },
  "feature_cards": {
    "cards": [
      { "icon": "cog", "title": "Een platform", "description": "...", "link_text": "Read more", "link_url": "/..." }
    ]
  },
  "about_opms": { "label": "OVER OPMS", "heading": "...", "paragraph": "...", "bullets": [], "link_text": "...", "link_url": "...", "image": "..." },
  "how_it_works": { "title": "Hoe het werkt", "steps": [] },
  "user_features": { "left_title": "...", "left_items": [], "right_title": "...", "right_items": [] },
  "competition": { "heading": "...", "paragraph": "...", "boxes": [] },
  "latest_updates": { "title": "Laatste updates" },
  "bottom_cta": { "heading": "...", "subtext": "...", "cta_primary_text": "...", "cta_primary_url": "...", "cta_secondary_text": "...", "cta_secondary_url": "..." }
}
```

Articles for “Latest updates” are not included; use **GET /api/blog** to fetch the latest posts.

---

## Settings

Site, theme, SEO, contact/map, cookie, hero images, header/footer CTA, and external codes.

### Get settings

**GET** `/api/settings`  
**Auth:** None (public). Restricted by allowed origins.

Returns grouped settings for the frontend. Response is a JSON object (not wrapped in `data`).

**Example request:** `GET /api/settings`

**Example response:** `200 OK`

```json
{
  "site": {
    "name": "My Site",
    "tagline": "...",
    "description": "...",
    "logo": "https://...",
    "favicon": "https://...",
    "email": "...",
    "phone": "...",
    "address": "...",
    "copyright_footer": "..."
  },
  "theme": {
    "base_color": "...",
    "accent_color": "...",
    "primary_color": "...",
    "secondary_color": "...",
    "natural_color": "...",
    "font_sans": "...",
    "font_outfit": "...",
    "font_size_h1": "...",
    "font_size_h2": "...",
    "font_size_h3": "...",
    "font_size_h4": "...",
    "font_size_h5": "...",
    "font_size_h6": "...",
    "font_size_p": "..."
  },
  "seo": {
    "meta_title": "...",
    "meta_description": "...",
    "meta_keywords": "...",
    "google_analytics_id": "..."
  },
  "contact": {
    "map_latitude": "...",
    "map_longitude": "...",
    "map_zoom": 13
  },
  "cookie": {
    "banner_enabled": true,
    "intro_title": "...",
    "intro_summary": "...",
    "preferences_title": "...",
    "preferences_summary": "...",
    "settings_label": "...",
    "settings_url": "...",
    "policy_url": "...",
    "category_functional_label": "...",
    "category_functional_description": "...",
    "category_analytics_label": "...",
    "category_analytics_description": "...",
    "category_marketing_label": "...",
    "category_marketing_description": "..."
  },
  "hero": {
    "contact": "https://...",
    "blog": "https://...",
    "solutions_index": "https://...",
    "solutions_show": "https://...",
    "modules_index": "https://...",
    "modules_show": "https://...",
    "academy": "https://..."
  },
  "header": {
    "cta_button_text": "...",
    "cta_button_url": "..."
  },
  "footer": {
    "cta_title": "...",
    "cta_subtitle": "...",
    "cta_description": "...",
    "cta_button_text": "...",
    "cta_button_url": "..."
  },
  "external_codes": []
}
```

---

## Menus

Header (mega menu), footer, and sticky menu structures for headless consumption.

### All menus

**GET** `/api/menus`  
**Auth:** None (public). Restricted by allowed origins.

Returns header and footer menus in one response.

**Example response:** `200 OK`

```json
{
  "header": {
    "items": [
      {
        "id": 1,
        "title": "Oplossingen",
        "subtitle": null,
        "description": null,
        "url": "/oplossingen",
        "slug": null,
        "page_type": "custom",
        "order": 0,
        "tags": [],
        "align": 1,
        "children": []
      }
    ],
    "settings": {
      "sticky": false,
      "login_link_enabled": true,
      "login_link_url": "#"
    }
  },
  "footer": {
    "columns": [
      {
        "column": 1,
        "links": [
          { "id": 1, "title": "Privacy", "url": "/legal/privacy", "order": 0 }
        ]
      }
    ]
  }
}
```

---

### Header menu

**GET** `/api/menus/header`  
**Auth:** None (public). Restricted by allowed origins.

Returns the mega menu tree plus header settings (sticky, login link).

**Response:** `items` (array of menu nodes with `children`), `settings` (sticky, login_link_enabled, login_link_url).

---

### Footer menu

**GET** `/api/menus/footer`  
**Auth:** None (public). Restricted by allowed origins.

Returns footer links grouped by column.

**Response:** `columns` (array of `{ column, links }` where each link has `id`, `title`, `url`, `order`).

---

### Sticky menu

**GET** `/api/menus/sticky`  
**Auth:** None (public). Restricted by allowed origins.

Returns sticky menu items (e.g. for mobile or secondary nav).

**Response:** `items` (array of `id`, `title`, `icon`, `link`, `link_type`, `is_external`, `sort_order`).

---

## Documentation

Documentation sections and pages (no versioning).

### List doc sections

**GET** `/api/docs`  
**Auth:** None (public). Restricted by allowed origins.

Returns active doc sections with their pages tree.

**Example response:** `200 OK` — Collection of doc section resources (id, title, slug, description, sort_order, pages).

---

### Search documentation

**GET** `/api/docs/search`  
**Auth:** None (public). Restricted by allowed origins.

**Query parameters:**

| Parameter  | Type   | Required | Description                |
|-----------|--------|----------|----------------------------|
| `q`       | string | yes      | Search query (min. 2 chars). |

**Example response:** `200 OK`

```json
{
  "results": [
    {
      "id": 1,
      "title": "Getting started",
      "url": "https://...",
      "section": "Introduction",
      "excerpt": "..."
    }
  ],
  "query": "getting",
  "count": 5
}
```

---

### Get doc page

**GET** `/api/docs/{section}/{page}`  
**Auth:** None (public). Restricted by allowed origins.

Returns a single doc page by section slug and page slug.

**Example:** `GET /api/docs/getting-started/introduction`

**Error:** `404 Not Found` if section or page is missing or inactive.

---

## Course / Academy

Course categories, videos, and live sessions (including registration).

### Course index

**GET** `/api/course`  
**Auth:** None (public). Restricted by allowed origins.

Returns featured session, upcoming sessions, recent videos, presenters, categories, and stats. Optional search via `q`.

**Query parameters:**

| Parameter | Type   | Description        |
|----------|--------|--------------------|
| `q`      | string | Search term (min. 2 chars). |

**Example response:** `200 OK`

```json
{
  "data": {
    "featured_session": { ... },
    "upcoming_sessions": [ ... ],
    "recent_videos": [ ... ],
    "presenters": [ { "id": 1, "name": "...", "avatar": "https://...", "sort_order": 0 } ],
    "categories": [ ... ],
    "search_query": "",
    "stats": {
      "video_count": 42,
      "total_duration_seconds": 36000,
      "hero_duration": "10 hr 0 min"
    }
  }
}
```

---

### Course categories

**GET** `/api/course/categories`  
**Auth:** None (public). Restricted by allowed origins.

Returns list of active course categories (structure depends on resource).

---

### Course category by slug

**GET** `/api/course/category/{slug}`  
**Auth:** None (public). Restricted by allowed origins.

Returns a single category with its videos.

**Error:** `404 Not Found` if slug not found or inactive.

---

### Course video by slug

**GET** `/api/course/video/{slug}`  
**Auth:** None (public). Restricted by allowed origins.

Returns a single video with metadata.

**Error:** `404 Not Found` if slug not found or inactive.

---

### Live sessions

**GET** `/api/course/live-sessions`  
**Auth:** None (public). Restricted by allowed origins.

Returns upcoming live sessions (full list) and past sessions (paginated), plus `past_meta`.

**Example response:** `200 OK`

```json
{
  "upcoming": [ ... ],
  "past": [ ... ],
  "past_meta": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 12,
    "total": 15
  }
}
```

---

### Live session recordings

**GET** `/api/course/live-sessions/recordings`  
**Auth:** None (public). Restricted by allowed origins.

Returns paginated past (recorded) live sessions.

**Query parameters:** `per_page` (1–50, default 12).

**Response:** `data` (array), `meta` (current_page, last_page, per_page, total).

---

### Live session by slug

**GET** `/api/course/live-sessions/{slug}`  
**Auth:** None (public). Restricted by allowed origins.

Returns a single live session with presenters.

**Error:** `404 Not Found` if slug not found or inactive.

---

### Register for live session

**POST** `/api/course/live-sessions/{slug}/register`  
**Auth:** None (public). Rate-limited (`throttle:forms`).

Register for a live session.

**Body (JSON or form):**

| Field              | Type    | Required | Description        |
|--------------------|---------|----------|--------------------|
| `name`             | string  | yes      | Full name.         |
| `email`            | string  | yes      | Email.             |
| `organization`     | string  | yes      | Organization.      |
| `marketing_consent`| boolean | no       | Newsletter/consent. |

**Example response:** `201 Created`

```json
{
  "success": true,
  "message": "Je bent aangemeld voor deze sessie."
}
```

**Errors:** `404` (session not found), `422` (validation failed).

---

## Pricing

Pricing plans, boosters, and configurator.

### Pricing index

**GET** `/api/prijzen`  
**Auth:** None (public). Restricted by allowed origins.

Returns plans, boosters, and features (cached).

**Example response:** `200 OK`

```json
{
  "data": {
    "plans": [ ... ],
    "boosters": [ ... ],
    "features": [ ... ]
  }
}
```

---

### Pricing configurator

**GET** `/api/prijzen/configurator`  
**Auth:** None (public). Restricted by allowed origins.

Returns boosters for the configurator.

**Response:** `data.boosters` (array).

---

### Pricing plan by slug

**GET** `/api/prijzen/{slug}`  
**Auth:** None (public). Restricted by allowed origins.

Returns a single plan plus plans, boosters, and features available for that plan.

**Error:** `404 Not Found` if slug not found or inactive.

---

## Trial

Trial (proefversie) page data and success message.

### Trial page data

**GET** `/api/proefversie`  
**Auth:** None (public). Restricted by allowed origins.

Returns data for the trial form (e.g. list of solutions).

**Example response:** `200 OK`

```json
{
  "data": {
    "solutions": [
      { "id": 1, "title": "...", "subtitle": "..." }
    ]
  }
}
```

---

### Trial success

**GET** `/api/proefversie/success`  
**Auth:** None (public). Restricted by allowed origins.

Returns a success message after trial request.

**Example response:** `200 OK`

```json
{
  "data": {
    "message": "Aanvraag ontvangen. We nemen zo snel mogelijk contact met je op."
  }
}
```

---

## Vacancies

Job vacancies and applications.

### List vacancies

**GET** `/api/vacancies`  
**Auth:** None (public). Restricted by allowed origins.

Returns paginated vacancies with optional filters and available filter values.

**Query parameters:**

| Parameter   | Type   | Description                    |
|------------|--------|--------------------------------|
| `search`   | string | Search in title/description/department. |
| `type`     | string | Filter by type.                |
| `location` | string | Filter by location.            |
| `department` | string | Filter by department.        |
| `category` | string | Filter by category.            |
| `per_page` | number | Items per page (1–50, default 10). |

**Example response:** `200 OK`

```json
{
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 10,
    "total": 15,
    "from": 1,
    "to": 10
  },
  "filters": {
    "departments": ["Engineering", "Sales"],
    "locations": ["Amsterdam", "Remote"],
    "categories": ["Full-time", "Part-time"]
  }
}
```

---

### Vacancy by slug

**GET** `/api/vacancies/{slug}`  
**Auth:** None (public). Restricted by allowed origins.

Returns a single active vacancy.

**Error:** `404 Not Found` if slug not found or inactive.

---

### Vacancy apply data

**GET** `/api/vacancies/{slug}/apply`  
**Auth:** None (public). Restricted by allowed origins.

Returns vacancy details for the application form (same as single vacancy, wrapped in `data`).

**Error:** `404 Not Found` if slug not found or inactive.

---

### Submit job application

**POST** `/api/vacancies/{slug}/apply`  
**Auth:** None (public). Rate-limited (`throttle:forms`).

Submit a job application. Send as **form data** or **JSON**; use multipart/form-data if including a resume file.

**Body:**

| Field         | Type   | Required | Description                    |
|---------------|--------|----------|--------------------------------|
| `name`        | string | yes      | Full name.                     |
| `email`       | string | yes      | Email.                         |
| `phone`       | string | no       | Phone (max 20).                |
| `cover_letter`| string | no       | Cover letter text.             |
| `resume`      | file   | no       | PDF or DOC/DOCX, max 5MB.      |
| `linkedin_url`| string | no       | LinkedIn URL.                  |
| `portfolio_url` | string | no     | Portfolio URL.                 |
| `repo_url`    | string | no       | Repository URL.                 |

**Example response:** `201 Created`

```json
{
  "success": true,
  "message": "Je sollicitatie is succesvol verzonden!",
  "data": { "id": 123 }
}
```

**Errors:** `404` (vacancy not found), `422` (validation failed).

---

## Sitemap and robots

### Sitemap (JSON)

**GET** `/api/sitemap`  
**Auth:** None (public). Restricted by allowed origins.

Returns sitemap as JSON: array of `{ loc, priority }`. Paths are API-relative; the frontend can build full URLs from its own origin.

**Example response:** `200 OK`

```json
{
  "data": [
    { "loc": "/api/homepage", "priority": "1.0" },
    { "loc": "/api/contact", "priority": "0.8" },
    { "loc": "/api/blog", "priority": "0.9" },
    { "loc": "/api/pages/over-ons", "priority": "0.6" }
  ]
}
```

---

### Robots.txt

**GET** `/api/robots-txt`  
**Auth:** None (public). Restricted by allowed origins.

Returns the robots.txt content as **plain text** (`Content-Type: text/plain; charset=UTF-8`). Content is managed in the admin and cached.

**Example response:** `200 OK` (body is raw text, e.g. `User-agent: *\nAllow: /\n`).

---

## Media

There is no dedicated media library table; images are embedded in content (pages, blog, settings). Use the fields `image`, `icon`, or `image_url` returned by pages, blog posts, and **GET /api/settings** for stable asset URLs.

### List media (placeholder)

**GET** `/api/media`  
**Auth:** None (public). Restricted by allowed origins.

Returns a paginated list of media assets. **Currently returns an empty list**; this endpoint is reserved for a future media library.

**Query parameters:** `per_page` (1–100, default 12), `page`.

**Example response:** `200 OK`

```json
{
  "data": [],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 12,
    "total": 0,
    "from": null,
    "to": null
  }
}
```

---

## Analytics

Analytics endpoints are under **`/api/analytics/`**. They are rate-limited (`throttle:api`) and public (no auth). Track, batch-track, guest-activity, and performance are best-effort: on failure they return `200` with `status: 'error'` so client code does not break.

### Track page view

**POST** `/api/analytics/track`  
**Auth:** None (public). Rate-limited.

**Body (JSON):**

| Field        | Type   | Required | Description        |
|-------------|--------|----------|--------------------|
| `url`       | string | yes      | Page URL (max 500). |
| `page_title`| string | no       | Document title.     |
| `referrer`  | string | no       | Referrer URL.       |
| `user_agent`| string | no       | User agent.         |
| `metadata`  | object | no       | Extra key-value.    |

**Example response:** `200 OK`

```json
{ "status": "tracked" }
```

or `{ "status": "skipped" }` or `{ "status": "error" }`.

---

### Batch track (SPA)

**POST** `/api/analytics/batch-track`  
**Auth:** None (public). Rate-limited.

**Body (JSON):**  
`views`: array of objects (max 10). Each object must have `url`; optional: `page_title`, `referrer`, `metadata`.

**Example response:** `200 OK`

```json
{
  "status": "tracked",
  "count": 3
}
```

or `{ "status": "error" }`.

---

### Guest activity

**POST** `/api/analytics/guest-activity`  
**Auth:** None (public). Rate-limited.

Tracks guest activity (e.g. activity type, URL, metadata). Request body schema is defined in the controller. Returns `200` with `status`: `tracked`, `skipped`, or `error`.

---

### Performance

**POST** `/api/analytics/performance`  
**Auth:** None (public). Rate-limited.

Submit performance metrics (e.g. navigation timing, Core Web Vitals). Exact schema is defined in the controller. Returns `200` with a status.

---

### Stats

**GET** `/api/analytics/stats`  
**Auth:** None (public). Rate-limited.

Returns aggregated stats (if implemented). Response shape depends on the application.

---

## Errors

- **403 Forbidden**  
  Returned when the request Origin/Referer is not in the allowed list (see [CORS and security](#cors-and-security)). Body: `{"message": "Origin not allowed."}`.

- **404 Not Found**  
  Returned when a single resource is requested by slug/path and no **active** item exists (e.g. `/api/pages/unknown`, `/api/blog/unknown`). Send `Accept: application/json` to get JSON.

- **422 Unprocessable Entity**  
  Validation errors (e.g. contact form, vacancy apply). Body includes `message` and `errors` (field → array of messages).

- **500 Internal Server Error**  
  Server or unexpected error. Some endpoints may return `success: false` and `message` in the body.

- **429 Too Many Requests**  
  Rate limiting (e.g. analytics, forms). Retry after the indicated period.

---

## CORS and security

- **Allowed origins:** Requests to content and analytics endpoints are checked against `FRONTEND_ALLOWED_ORIGINS` (comma-separated list in `.env`). If empty or not set, all origins are allowed. In production, set this to your SPA domain(s), e.g. `https://your-react-app.com`.
- **Content endpoints** are **public** — no login or Bearer token. Security is via origin restriction only.
- Search, contact forms, vacancy apply, blog comments, and live-session registration are public and rate-limited. Use HTTPS in production.

---

## Quick reference

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/pages` | — | List active pages (paginated) |
| GET | `/api/pages/{slug}` | — | Single page by slug |
| GET | `/api/blog` | — | List blog posts (paginated, search, category) |
| GET | `/api/blog/{slug}` | — | Single blog post by slug |
| POST | `/api/blog/{slug}/comments` | — | Post comment |
| POST | `/api/blog/{slug}/comments/{id}/like` | — | Like comment |
| POST | `/api/blog/{slug}/comments/{id}/dislike` | — | Dislike comment |
| GET | `/api/legal/{slug}` | — | Single legal page by slug |
| GET | `/api/static/{slug}` | — | Single static page by slug |
| GET | `/api/settings` | — | Site, theme, SEO, cookie, header/footer settings |
| GET | `/api/homepage` | — | Homepage content sections |
| GET | `/api/menus` | — | Header + footer menus |
| GET | `/api/menus/header` | — | Header (mega) menu |
| GET | `/api/menus/footer` | — | Footer menu |
| GET | `/api/menus/sticky` | — | Sticky menu items |
| GET | `/api/docs` | — | Doc sections with pages tree |
| GET | `/api/docs/search?q=` | — | Search documentation |
| GET | `/api/docs/{section}/{page}` | — | Single doc page |
| GET | `/api/course` | — | Course index (sessions, videos, categories) |
| GET | `/api/course/categories` | — | Course categories |
| GET | `/api/course/category/{slug}` | — | Single category |
| GET | `/api/course/video/{slug}` | — | Single video |
| GET | `/api/course/live-sessions` | — | Upcoming + past live sessions |
| GET | `/api/course/live-sessions/recordings` | — | Past sessions (paginated) |
| GET | `/api/course/live-sessions/{slug}` | — | Single live session |
| POST | `/api/course/live-sessions/{slug}/register` | — | Register for session |
| GET | `/api/modules` | — | List modules |
| GET | `/api/modules/{slug}` | — | Single module |
| GET | `/api/features` | — | List features |
| GET | `/api/features/{anchor}` | — | Single feature by anchor |
| GET | `/api/solutions` | — | List solutions |
| GET | `/api/solutions/{anchor}` | — | Single solution by anchor |
| GET | `/api/prijzen` | — | Pricing index (plans, boosters, features) |
| GET | `/api/prijzen/configurator` | — | Pricing configurator boosters |
| GET | `/api/prijzen/{slug}` | — | Single pricing plan |
| GET | `/api/proefversie` | — | Trial page data |
| GET | `/api/proefversie/success` | — | Trial success message |
| GET | `/api/sitemap` | — | Sitemap as JSON |
| GET | `/api/robots-txt` | — | Robots.txt (text/plain) |
| GET | `/api/media` | — | Media list (placeholder; empty) |
| GET | `/api/vacancies` | — | List vacancies (paginated, filterable) |
| GET | `/api/vacancies/{slug}` | — | Single vacancy |
| GET | `/api/vacancies/{slug}/apply` | — | Vacancy apply form data |
| POST | `/api/vacancies/{slug}/apply` | — | Submit job application |
| GET | `/api/changelog` | — | Changelog index (paginated) |
| GET | `/api/changelog/{slug}` | — | Single changelog entry |
| GET | `/api/contact` | — | Contact page data |
| POST | `/api/contact/verstuur` | — | Submit contact form |
| GET | `/api/search?q=` | — | Full-text search |
| GET | `/api/search/suggestions?q=` | — | Search suggestions |
| POST | `/api/analytics/track` | — | Track page view |
| POST | `/api/analytics/batch-track` | — | Batch track (SPA) |
| POST | `/api/analytics/guest-activity` | — | Guest activity |
| POST | `/api/analytics/performance` | — | Performance metrics |
| GET | `/api/analytics/stats` | — | Analytics stats |
