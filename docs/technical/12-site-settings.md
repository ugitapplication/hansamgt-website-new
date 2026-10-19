# 12 — Site Settings

> **Purpose:** Document the `settings` table module: every key, its tab in the admin panel, clear-vs-default semantics, caching, image handling, and the test-email action.
> **Audience:** Developers and administrators.
> **Last reviewed:** 2026-10-19
> **Evidence basis:** `app/Support/Settings.php` (402 lines, read in full), `app/Filament/Pages/ManageSettings.php` (read to line 364), `config/site.php`, `database/migrations/2026_10_17_000002_create_settings_table.php`, `tests/Feature/SettingsCacheTest.php`.

**Verification legend:** ✅ source-verified · 🟡 runtime-unverified · ⚠️ partial · ⛔ missing · ❓ unknown

---

## 1. Purpose

Before this module existed, the logo, phone number, email, address and social links were duplicated across the header, footer, contact page and SEO partial. `settings` centralises them into one key/value table editable at **Settings → Site Settings** (`/admin/settings`).

**Design rule** (`config/site.php:14-19`): only **public** values live in the table. Secrets — mail credentials, `APP_KEY`, database credentials — stay in `.env` and are deliberately not editable, because a database row is plaintext, readable by every admin, and stolen with any database leak.

---

## 2. Storage

Table `settings` (`2026_10_17_000002_…:22-27`):

| Column | Type | Constraint |
|---|---|---|
| `id` | bigint unsigned | PK |
| `key` | varchar(255) | unique |
| `value` | text | nullable |
| `created_at`, `updated_at` | timestamp | nullable |

Model `app/Models/Setting.php` is minimal: `$fillable = ['key','value']`.

**No seeder populates this table.** Defaults come from `config/site.php`; the table starts empty and stays empty until an admin saves the settings form. ✅

---

## 3. Resolution logic

`App\Support\Settings` is a static facade-like class with a per-request snapshot plus a shared cache.

### 3.1 Read path

```
Settings::get($key)
  └─ Settings::all()
       ├─ static $cache (per request)        → return
       ├─ Cache::get('site-settings:v1')     → return if array
       ├─ Setting::pluck('value','key')      → write cache, return
       └─ on Throwable                        → return [] (defaults only, NOT cached)
```

Lines: `all()` 91-119, `readCached()` 133-142, `writeCached()` 151-158.

Key properties:

- A **failed** DB read is never written to the shared cache (110-113), so a transient outage does not pin defaults for a day.
- A **successful empty** table is cached as `[]` (126-127 explains why an empty array must be distinguishable from a failure).
- The cache store is `database` by default, so `readCached()`/`writeCached()` are individually guarded against the same outage that broke the DB.

### 3.2 Default and clear semantics

`get()` (167-186):

| Stored value | In `CLEARABLE_KEYS`? | Result |
|---|---|---|
| non-empty | — | stored value |
| `null` or `''` | ✅ yes | **`null`** (intentionally blank) |
| `null` or `''` | ⛔ no | `$default` argument, else `config('site.defaults.<key>')` |
| key absent | — | `$default` argument, else config default |

`CLEARABLE_KEYS` (32-44):

```
facebook, linkedin, youtube, twitter, instagram,
whatsapp, phone_emergency, fax, latitude, longitude, opening_hours
```

Rationale (18-30): these keys have real defaults, so without this list, clearing a field in the panel would silently restore the value the editor was trying to remove. **Image keys are deliberately excluded** — clearing an upload should return to the bundled logo.

⚠️ This asymmetry is subtle and is the most likely thing to break in future edits. `tests/Feature/SettingsCacheTest.php` does **not** test `CLEARABLE_KEYS` directly.

### 3.3 Writes

| Method | Behaviour |
|---|---|
| `set($key, $value)` (229-234) | `updateOrCreate` then `flush()` |
| `setMany($values)` (241-255) | loop `updateOrCreate`, booleans → `'1'`/`'0'`, empty → `null`, then `flush()` |
| `flush()` (263-273) | clears static cache + URL memo + `Cache::forget('site-settings:v1')` |

`flush()` is wrapped in `try/catch` because a cache outage must not fail a save.

### 3.4 Cache key and TTL

| Item | Value |
|---|---|
| Key | `site-settings:v1` (`Settings.php:53`) |
| TTL | `86400` s = 24 h (`63`) |
| Invalidation | every write through `set`/`setMany` |
| Safety net | TTL bounds staleness to one day if a row is changed by raw SQL/tinker |

⚠️ The key is **versioned by hand**. Changing the cached payload shape requires bumping `v1` — there is no automatic purge. `SettingsCacheTest` mirrors the literal key deliberately (line 44) so a rename forces a test change.

---

## 4. Admin page — `/admin/settings`

`app/Filament/Pages/ManageSettings.php`.

| Property | Value |
|---|---|
| Class | `App\Filament\Pages\ManageSettings` |
| Slug | `settings` (47) |
| Group | Settings (39) |
| Sort | 1 (41) |
| Title | "Site Settings" (45) |
| State path | `data` (83) |
| Save action | "Save settings", `mod+s` (293-299) |
| Header action | "Send test email" (304-317) |

`mount()` fills the form from `currentValues()` (76-79). Image keys are intentionally left blank when unset rather than pre-filled with a config path (56-63), because Filament cannot preview a `public/`-relative default.

### 4.1 Tab: Contact (91-158)

| Field | Type | Rules | Effect |
|---|---|---|---|
| `enquiry_recipient` | email | required, max 190 | contact-form recipient |
| `email` | email | required, max 190 | footer, contact page, schema |
| `phone_primary` | tel | required, max 30 | header/footer/contact, schema |
| `phone_emergency` | tel | max 30 | hidden when empty |
| `whatsapp` | tel | max 30 | gates the float button |
| `fax` | text | max 50 | contact strip, hidden when empty |
| `postal_address` | textarea | required, rows 2 | contact page |
| `street_address` | text | — | schema.org |
| `address_locality` | text | — | schema.org |
| `postal_code` | text | — | schema.org |
| `address_country` | text | — | schema.org (e.g. `BD`) |
| `whatsapp_enabled` | toggle | default true | second gate for the float button |

### 4.2 Tab: Branding (160-178)

| Field | Type | Upload |
|---|---|---|
| `logo` | FileUpload | `public` disk, `branding/` |
| `footer_logo` | FileUpload | `branding/` |
| `favicon` | FileUpload | `branding/` |
| `og_image` | FileUpload | `branding/` |
| `footer_about` | textarea | max 400 |

### 4.3 Tab: Social profiles (180-191)

`facebook`, `linkedin`, `youtube`, `twitter`, `instagram` — each URL-validated, max 255. Empty = icon hidden (`Settings::socialLinks`, 367-388).

### 4.4 Tab: Analytics & legal (193-218)

| Field | Type | Notes |
|---|---|---|
| `ga4_enabled` | toggle | overridden by `GA4_ENABLED=false` |
| `ga4_id` | text, max 30 | measurement ID |
| `consent_version` | text, numeric, integer, required | bump to re-prompt |
| `privacy_updated` | DatePicker | shown on privacy page |

### 4.5 Tab: SEO & structured data (220-268)

| Field | Rules |
|---|---|
| `legal_name` | required, max 255 |
| `site_url` | url, required, max 255 |
| `parent_name` | max 255 |
| `parent_url` | url, max 255 |
| `founding_year` | numeric, integer, 1900-2100 |
| `description` | textarea, max 300 |
| `latitude` | max 20 |
| `longitude` | max 20 |
| `opening_hours` | max 120 |

⚠️ Latitude/longitude here are **plain text**, unlike the numeric min/max validation on the Offices resource. A non-numeric value is still `filled()`, so `seo.blade.php:59-64` emits a `geo` block and the `(float)` cast turns the value into `0.0` — a real coordinate off the coast of Africa, published as fact. Minor, but worth knowing.

---

## 5. Image settings

`Settings::imageUrl($key)` (282-303):

1. Trim; empty → `''`.
2. Absolute `http(s)` → returned unchanged.
3. Exists on `public` disk → `url(Storage::disk('public')->url($path))`.
4. Otherwise → `asset(ltrim($path,'/'))` (i.e. a `public/` path).

Memoised per path in `$urlCache` (78), cleared by `flush()`.

⚠️ Step 3 produces a `/storage/...` URL. With the symlink missing, uploaded branding images will 404 and **will not** fall back to `asset()`, because the file *does* exist on the disk. See [14 — Media](14-media-and-image-pipeline.md#42-the-missing-symlink).

---

## 6. Send test email

`sendTestEmail()` (334-…):

1. Reads `Settings::get('enquiry_recipient')`; blank → danger notification "No enquiry recipient is set" (338-346).
2. `Mail::raw(...)` to that address with subject "HANSA website — test email" (348-355).
3. On exception: `report()` + danger notification containing `Str::limit($exception->getMessage(), 300)` (356-362).

⚠️ **Information disclosure:** SMTP exceptions frequently include the host, port, and sometimes the username. Showing that text in the admin UI is acceptable for a trusted operator but should not be pasted into a support ticket. It is also visible to *every* admin, since there are no roles.

---

## 7. Consumers of each key

| Key | Read by |
|---|---|
| `logo`, `footer_logo` | `partials/header.blade.php:5,27`, `partials/footer.blade.php` |
| `favicon`, `og_image` | `partials/seo.blade.php:22,207-208` |
| `legal_name` | header alt text, footer aria-label, schema |
| `site_url` | schema `@id`s, sitemap base (`GenerateSitemap.php:46`) |
| `description` | schema Organization |
| `footer_about` | footer |
| `parent_name`, `parent_url` | header GIF link, schema `parentOrganization` |
| `founding_year` | schema `foundingDate` |
| `phone_primary` | `phoneDisplay()`, `phoneHref()`, header/footer/contact/schema |
| `phone_emergency` | contact surfaces (when set) |
| `whatsapp`, `whatsapp_enabled` | `whatsappHref()`, `partials/whatsapp-float` |
| `fax` | contact strip |
| `email` | footer, contact, careers, schema, confirmation email |
| `enquiry_recipient` | `ContactController`, test-email action |
| `postal_address` | contact page |
| `street_address`, `address_locality`, `postal_code`, `address_country` | schema PostalAddress |
| `facebook`…`instagram` | `socialLinks()`, footer, contact cards, schema `sameAs` |
| `ga4_enabled`, `ga4_id` | `Consent::analyticsEnabled/measurementId` |
| `consent_version` | `Consent::version()` |
| `privacy_updated` | privacy page |

✅ All of the above were traced in source.

---

## 8. Helper methods

| Method | Behaviour | Lines |
|---|---|---|
| `filled($key)` | `filled(get($key))` | 191-194 |
| `bool($key, $default=false)` | reads raw stored value, falls back to config default, `FILTER_VALIDATE_BOOL` | 199-210 |
| `default($key)` | `config('site.defaults.'.$key)`, booleans → `'1'`/`'0'` | 215-224 |
| `imageUrl($key)` | see §5 | 282-303 |
| `phoneDisplay()` | strips leading `+88`/`88` | 309-318 |
| `phoneHref()` | `tel:` + digits and `+` only | 323-328 |
| `whatsappHref()` | digits only, strips `00`, requires ≥ 8 digits, returns `https://wa.me/<digits>` or null | 340-357 |
| `socialLinks()` | ordered list of non-empty profiles | 367-388 |
| `sameAs()` | URLs from `socialLinks()` | 395-401 |

`whatsappHref()` is covered by `tests/Feature/WhatsappFloatTest.php:126-146` with 8 cases (plus sign, spaces/dashes, parentheses, no prefix, `00` prefix, empty, placeholder text, too short). ✅

---

## 9. Cache behaviour tests

`tests/Feature/SettingsCacheTest.php` — 10 tests:

| Test | Asserts |
|---|---|
| request-local snapshot does not leak between tests | static reset |
| successful read stores raw rows | cache payload |
| empty table cached as a successful read | `[]` is cached |
| warm cache supplies values | cache-first |
| failed DB read returns defaults and poisons nothing | no write on failure |
| recovery after failure populates cache | self-healing |
| `set` invalidates shared cache | write-through |
| `setMany` invalidates | write-through |
| cached payload holds raw paths, not resolved URLs | no host baked in |
| unavailable cache store does not break reads/invalidation | guarded cache |

🟡 These are the strongest tests in the repo. They were not executed.

---

## 10. Known limitations

| Limitation | Detail |
|---|---|
| Cache key is manual | bump `site-settings:v1` when the payload shape changes |
| No per-key cache | one row change invalidates everything (acceptable at this scale) |
| No revision history | previous values are unrecoverable |
| No validation on stored keys | `Settings::set()` accepts any key, including typos |
| Latitude/longitude not range-checked in settings | plain text fields (contrast with Offices) |
| Test email leaks exception text | 300-char message in the admin UI |
| `GA4_ENABLED` duplicated | `config/site.php:78` and `config/analytics.php:18` |
| No audit trail | no record of who changed what |

---

## Related chapters

- [05 — Configuration Reference](05-configuration-reference.md) — env vs DB layers
- [11 — Admin Panel Reference](11-admin-panel-reference.md) — panel overview
- [13 — Database & Data Dictionary](13-database-and-data-dictionary.md#32-settings)
- [14 — Media & Image Pipeline](14-media-and-image-pipeline.md)
- [15 — SEO & Structured Data](15-seo-and-structured-data.md)
- [16 — Analytics, Consent & Privacy](16-analytics-consent-and-privacy.md)
- [19 — Testing & QA](19-testing-and-qa.md)
