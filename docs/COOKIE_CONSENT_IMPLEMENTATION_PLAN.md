# Cookie Consent Implementation Plan

Based on analysis of the `resources/docs` folder, specifically the `Cookiebeleid.html` (Dutch Cookie Policy) document, here is a comprehensive plan for implementing cookie consent settings that comply with AVG/ePrivacy regulations.

## Current State Analysis

### ✅ Already Implemented
- Basic GDPR banner component (`GdprBanner.php`, `gdpr-banner.blade.php`)
- Three cookie categories: Functional, Analytics, Marketing
- LocalStorage-based consent storage
- Basic UI for accept/reject/manage preferences
- Cookie settings configuration in `organization.json`

### ❌ Missing/Needs Enhancement
- Detailed cookie registry (per-cookie information)
- Server-side consent tracking
- Cookie settings management page
- Integration with actual cookie placement
- Third-party cookie management
- Cookie expiration tracking
- Admin interface for cookie management
- Dutch language support
- Consent audit trail

---

## Required Cookie Consent Features

### 1. Cookie Categories (As per Cookiebeleid.html)

#### A. Noodzakelijke (Necessary/Functional) Cookies
- **Status**: Always enabled, no consent required
- **Purpose**: Core website functionality, security, consent storage
- **Examples**: Session cookies, CSRF tokens, consent preference storage
- **Locked**: Yes (cannot be disabled)

#### B. Analytische (Analytical/Statistical) Cookies
- **Status**: Require explicit consent
- **Purpose**: Website usage analysis, visitor behavior tracking
- **Examples**: Google Analytics, Matomo, custom analytics
- **Data**: Anonymized IP, page views, session duration, referrer
- **Locked**: No (user can enable/disable)

#### C. Marketing (Tracking) Cookies
- **Status**: Require explicit consent
- **Purpose**: Cross-site tracking, personalized advertising, campaign measurement
- **Examples**: Facebook Pixel, Google Ads, LinkedIn Insight Tag
- **Data**: User profiles, browsing behavior across sites
- **Locked**: No (user can enable/disable)

---

## Implementation Checklist

### Phase 1: Database & Models

#### 1.1 Create Cookie Registry Database
- [ ] Migration: `create_cookies_table`
  - Fields: `id`, `name`, `category`, `provider`, `purpose`, `retention_days`, `domain`, `type` (first-party/third-party), `script_url`, `is_active`, `sort_order`, `created_at`, `updated_at`
- [ ] Migration: `create_cookie_consents_table`
  - Fields: `id`, `user_id` (nullable), `session_id`, `ip_address`, `consent_data` (JSON), `consent_date`, `expires_at`, `created_at`, `updated_at`
- [ ] Model: `Cookie.php`
  - Relationships, scopes, validation
- [ ] Model: `CookieConsent.php`
  - Relationships, consent validation

#### 1.2 Cookie Categories Configuration
- [ ] Update `GdprBanner` component to match Dutch policy:
  - Category keys: `functional` → `noodzakelijk`, `analytics` → `analytisch`, `marketing` → `marketing`
  - Dutch labels and descriptions
  - Ensure functional cookies are always locked

---

### Phase 2: Cookie Settings Management Page

#### 2.1 Frontend Cookie Settings Page
- [ ] Route: `/cookie-instellingen` or `/cookie-settings`
- [ ] Controller: `CookieSettingsController@show`
- [ ] View: `resources/views/front/cookie-settings.blade.php`
  - Display all cookie categories
  - Show detailed cookie list per category
  - Per-cookie information: name, provider, purpose, retention period
  - Toggle switches for each category
  - Save preferences button
  - Link to cookie policy

#### 2.2 Cookie List Display
- [ ] Component: `cookie-list.blade.php`
  - Table/cards showing:
    - Cookie name
    - Provider (first-party or third-party name)
    - Purpose description
    - Retention period (e.g., "30 days", "Session", "1 year")
    - Category badge
  - Filterable by category
  - Searchable

---

### Phase 3: Consent Management System

#### 3.1 Server-Side Consent Tracking
- [ ] API endpoint: `POST /api/cookie-consent`
  - Accept consent data from frontend
  - Store in `cookie_consents` table
  - Link to user session
  - Set expiration based on cookie retention periods
- [ ] Middleware: `CheckCookieConsent`
  - Check if user has given consent
  - Block third-party scripts if consent not given
  - Allow functional cookies always

#### 3.2 Consent Storage Enhancement
- [ ] Update `gdpr-banner.blade.php`:
  - Send consent to server via API
  - Keep localStorage as backup
  - Sync server and client state
- [ ] Consent expiration handling
  - Check if consent has expired
  - Re-show banner if expired
  - Auto-refresh consent if user is active

---

### Phase 4: Cookie Script Loading Integration

#### 4.1 Script Manager
- [ ] Service: `CookieScriptManager`
  - Register scripts by category
  - Load scripts only if consent given
  - Handle script dependencies
- [ ] Blade directive: `@cookieScript('category', 'script-url')`
  - Conditionally load scripts based on consent
- [ ] JavaScript helper: `loadCookieScript(category, scriptUrl)`
  - Dynamic script loading based on consent

#### 4.2 Third-Party Integration Examples
- [ ] Google Analytics integration
  - Only load if analytics consent given
  - Anonymize IP if required
- [ ] Facebook Pixel integration
  - Only load if marketing consent given
- [ ] YouTube embeds
  - Lazy load if marketing consent given
- [ ] Google Maps
  - Lazy load if marketing consent given

---

### Phase 5: Admin Interface

#### 5.1 Cookie Management Admin Panel
- [ ] Route: `/admin/cookies`
- [ ] Controller: `Admin\CookieController`
  - CRUD operations for cookies
  - Bulk import/export
  - Cookie scanning/audit
- [ ] Views:
  - `index.blade.php` - List all cookies
  - `create.blade.php` - Add new cookie
  - `edit.blade.php` - Edit cookie details
  - `import.blade.php` - Import cookie list

#### 5.2 Cookie Consent Analytics
- [ ] Route: `/admin/cookie-consents`
- [ ] Controller: `Admin\CookieConsentController`
  - View consent statistics
  - Consent rate per category
  - Consent over time
  - Export consent data (GDPR compliance)

---

### Phase 6: Localization & Compliance

#### 6.1 Dutch Language Support
- [ ] Update banner text to Dutch:
  - "We use cookies" → "Wij gebruiken cookies"
  - "Accept all" → "Alle cookies accepteren"
  - "Manage preferences" → "Voorkeuren beheren"
  - "Functional only" → "Alleen noodzakelijk"
  - Category labels and descriptions
- [ ] Translation files: `resources/lang/nl/cookie.php`

#### 6.2 Compliance Features
- [ ] Consent logging (audit trail)
  - Log all consent changes
  - Store IP address, timestamp, user agent
  - Required for GDPR compliance
- [ ] Consent withdrawal
  - Easy way to withdraw consent
  - Clear all non-essential cookies
  - Update consent record
- [ ] Cookie policy link
  - Link to `/cookie-policy` or configured URL
  - Ensure policy is up-to-date

---

### Phase 7: Advanced Features

#### 7.1 Cookie Scanner
- [ ] Automated cookie detection
  - Scan website for cookies being set
  - Identify third-party cookies
  - Suggest cookie registry entries
- [ ] Cookie audit report
  - List all cookies found
  - Categorize automatically
  - Flag missing consent requirements

#### 7.2 Consent Preferences API
- [ ] RESTful API for consent management
  - `GET /api/cookie-consent` - Get current consent
  - `POST /api/cookie-consent` - Update consent
  - `DELETE /api/cookie-consent` - Withdraw consent
- [ ] Webhook support (optional)
  - Notify external systems of consent changes

#### 7.3 Cookie Banner Customization
- [ ] Admin settings for banner:
  - Customize colors, text, position
  - Enable/disable categories
  - Custom messages per category
  - Banner style (modal, bottom bar, etc.)

---

## Configuration Requirements

### Settings to Add (via `organization.json` or database)

```json
{
  "cookie": {
    "settingsLocation": "footer, cookie-icoon",
    "bannerStyle": "modal",
    "bannerPosition": "center",
    "showOnFirstVisit": true,
    "consentExpirationDays": 365,
    "categories": {
      "functional": {
        "enabled": true,
        "locked": true,
        "label": "Noodzakelijke cookies",
        "description": "Technisch essentieel voor de werking van de website"
      },
      "analytics": {
        "enabled": false,
        "locked": false,
        "label": "Analytische cookies",
        "description": "Helpen ons begrijpen hoe bezoekers de website gebruiken"
      },
      "marketing": {
        "enabled": false,
        "locked": false,
        "label": "Marketing cookies",
        "description": "Voor het volgen van bezoekers en tonen van relevante advertenties"
      }
    }
  }
}
```

---

## Integration Points

### 1. Footer Link
- Add cookie settings link to footer
- Use `{{cookie.settingsLocation}}` from config
- Icon or text link

### 2. Cookie Policy Page
- Ensure cookie policy page exists
- Link from banner and settings page
- Use dynamic content from `Cookiebeleid.html` template

### 3. Privacy Policy Integration
- Reference cookie policy in privacy policy
- Link to cookie settings
- Explain data processing via cookies

---

## Testing Checklist

- [ ] Banner appears on first visit
- [ ] Banner doesn't appear after consent given
- [ ] Cookie settings page accessible
- [ ] Consent preferences save correctly
- [ ] Scripts only load with consent
- [ ] Functional cookies always work
- [ ] Consent withdrawal works
- [ ] Third-party cookies blocked without consent
- [ ] Consent expiration works
- [ ] Admin cookie management works
- [ ] Dutch translations correct
- [ ] Mobile responsive
- [ ] Accessibility (WCAG compliance)

---

## Priority Implementation Order

1. **High Priority** (GDPR Compliance):
   - Phase 1: Database & Models
   - Phase 2: Cookie Settings Page
   - Phase 3: Consent Management
   - Phase 6: Localization (Dutch)

2. **Medium Priority** (User Experience):
   - Phase 4: Script Loading Integration
   - Phase 7.2: Consent Preferences API

3. **Low Priority** (Advanced Features):
   - Phase 5: Admin Interface
   - Phase 7.1: Cookie Scanner
   - Phase 7.3: Banner Customization

---

## Notes

- The existing `GdprBanner` component is a good foundation but needs enhancement
- Current implementation uses only localStorage; server-side tracking is essential for compliance
- Cookie policy document mentions "Cookiebot" - consider if third-party CMP is needed or if custom solution is sufficient
- Ensure all cookie placements are gated by consent checks
- Regular cookie audits should be performed to maintain compliance

