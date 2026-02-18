# Securing the React app and headless CMS

Content from the API (e.g. pages) is **public**: no login is required to read it. Security is achieved without user auth by controlling **who can call the API** and **how** they call it.

---

## 1. HTTPS

- Use **HTTPS** in production so all traffic between the React app and the CMS API is encrypted.
- Configure your server or reverse proxy (Nginx, Caddy, Herd, etc.) to serve the API over HTTPS.

---

## 2. Block all external requesters except the React SPA URL

Only your React SPA URL(s) are allowed to call the API. Two layers enforce this:

### CORS (browser)

**Configuration:** `config/cors.php`  
**Env:** `CORS_ALLOWED_ORIGINS`

Browsers only allow requests from the listed origin(s). Other sites get a CORS error and the response is blocked by the browser.

### Server-side origin check

**Middleware:** `RestrictToAllowedOrigins` (alias `restrict.origins`)

The API also checks the **Origin** or **Referer** header on every request. If the request sends an origin that is **not** in the same allowed list as CORS, the server responds with **403 Forbidden**. That blocks:

- Other websites’ frontends (they send their own Origin).
- Non-browser clients (curl, Postman, other servers) that send an Origin/Referer that isn’t your React SPA URL.

**Allowed list:** Same as CORS (`CORS_ALLOWED_ORIGINS`). One config, two checks.

**Requests with no Origin/Referer** (e.g. Swagger UI on the same domain, Postman without Origin, cron jobs) are **allowed**. So you can still use the API from your own server or from the same host.

**Configuration:**

- **Development:** Leave `CORS_ALLOWED_ORIGINS` unset or set to `*` so any origin is allowed (no blocking).
- **Production:** Set to **only** your React SPA URL(s), comma-separated:

```env
# Only this origin can call the API (browser + server check)
CORS_ALLOWED_ORIGINS=https://myapp.com

# Multiple (e.g. app + preview)
CORS_ALLOWED_ORIGINS=https://myapp.com,https://preview.myapp.com
```

Result: external requesters are blocked except when the request comes from your React SPA URL (or has no Origin/Referer).

---

## 3. Optional API key (content routes)

You can require a **shared API key** for the public content routes (e.g. pages). Only clients that send the key can read content. This restricts access to “known” clients (your React app or a backend proxy).

**Configuration:** `config/services.php` → `cms_api_key`  
**Env:** `CMS_API_KEY`

- **Not set:** No key is required; any client can call the content API (rely on CORS + HTTPS + rate limiting).
- **Set:** The client must send the key in one of:
  - **Header:** `X-API-Key: <your-key>`
  - **Header:** `Authorization: Bearer <your-key>`

```env
CMS_API_KEY=your-long-random-secret-key
```

**In the React app:** Store the key in an env variable (e.g. `VITE_CMS_API_KEY`) and send it on every request to the content API:

```js
headers: {
  'X-API-Key': import.meta.env.VITE_CMS_API_KEY,
  'Accept': 'application/json',
}
```

**Important:** In a pure SPA, the key is visible in the frontend bundle. So this is **not** a secret; it’s a way to:

- Restrict access to clients that “know” the key.
- Stop random scripts or other sites from calling your API if they don’t have the key.
- Rotate the key if it’s abused (then update the React app’s env and redeploy).

For stronger secrecy, call the CMS from a **backend** (e.g. Next.js API route, Laravel BFF) that holds the key and proxies requests; the browser never sees the key.

---

## 4. Rate limiting

All API routes are limited to **60 requests per minute per client** (IP). This reduces abuse and simple DDoS.

- Configured in `routes/api.php` via `throttle:60,1`.
- When exceeded, the API returns `429 Too Many Requests`.

---

## 5. Sanctum (when you need login)

- **Content (pages):** No login; access is controlled by CORS + optional API key + HTTPS + rate limiting.
- **Admin / protected actions:** Use Sanctum as before:
  - **POST /api/v1/login** — Get a session or Bearer token.
  - **POST /api/v1/logout**, **GET /api/v1/user** — Require `auth:sanctum`.

So: **public content = no Sanctum**; **admin or future protected API = Sanctum**.

---

## 6. Checklist

| Measure | Purpose |
|--------|---------|
| **HTTPS** | Encrypt traffic between React and CMS. |
| **CORS** (`CORS_ALLOWED_ORIGINS`) | Only your app’s origin(s) can call the API from the browser. |
| **API key** (`CMS_API_KEY`) | Optional; only clients with the key can read content. |
| **Rate limit** (60/min) | Limit abuse and simple overload. |
| **Sanctum** | Only for admin / authenticated endpoints, not for public content. |

Together, these give you a secure link between the React app and the headless CMS when the site does not require login to get data.
