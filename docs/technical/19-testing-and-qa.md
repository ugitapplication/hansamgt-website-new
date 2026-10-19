# 19 — Testing & QA

> **Purpose:** Document exactly what is tested, what is not, how to run the suite, and how much confidence each test actually provides.
> **Audience:** Developers and reviewers.
> **Last reviewed:** 2026-10-19
> **Evidence basis:** `phpunit.xml`, all 9 test files under `tests/` (read in full), `tests/TestCase.php`, `composer.json` test script. **The suite was not executed** — shell unavailable (exit code 66).

**Verification legend:** ✅ source-verified · 🟡 runtime-unverified · ⚠️ partial · ⛔ missing · ❓ unknown

---

## 1. At a glance

| Metric | Value |
|---|---|
| Test framework | PHPUnit `^11.5.50` |
| Test files | **9** (plus `TestCase.php`) |
| Test methods | **59** |
| Suites | `Unit` (3 files), `Feature` (6 files) |
| Database | SQLite `:memory:` |
| Refresh strategy | `RefreshDatabase` on feature tests that need it |
| Coverage driver | ❓ not configured in `phpunit.xml` |
| Last executed | **Unknown** — no run was possible |
| CI | ⛔ none |

**59 methods is not 59 tests.** Three methods use `#[DataProvider]`, so the executed case count is higher:

| File | Methods | Data-provider cases | Effective cases |
|---|---|---|---|
| `PageHeadingTest` | 1 | 16 paths | 16 |
| `WhatsappFloatTest` | 6 | 1 provider × 5 pages + 1 provider × 8 numbers | 17 |
| `OfficeMapCoordinatesTest` | 10 | — | 10 |
| `ImageUrlTest` | 8 | — | 8 |
| `SettingsCacheTest` | 10 | — | 10 |
| `OfficeMapPageTest` | 16 | — | 16 |
| `StaticAssetCacheHeadersTest` | 6 | — | 6 |
| `ExampleTest` (Unit) | 1 | — | 1 |
| `ExampleTest` (Feature) | 1 | — | 1 |

🟡 Effective count = **85 test cases** (59 methods − 3 provider methods + 16 + 5 + 8 provider cases). The exact number depends on provider cardinality at runtime.

---

## 2. File-by-file

### 2.1 `tests/Unit/ExampleTest.php` — 1 method

`test_that_true_is_true`. Framework placeholder. ⛔ No value.

### 2.2 `tests/Feature/ExampleTest.php` — 1 method

`test_the_application_returns_a_successful_response` — `GET /` asserts 200. Useful as a smoke test, but the homepage swallows DB errors, so it passes even when the database is broken. ⚠️ Weak signal.

### 2.3 `tests/Unit/ImageUrlTest.php` — 8 methods

| Test | Asserts |
|---|---|
| same path checked against disk only once | memoisation |
| existing file → public disk URL | storage branch |
| missing file → public asset URL | fallback branch |
| different paths resolved independently | memo keying |
| absolute URLs unchanged | passthrough |
| null/empty/blank → `''` | edge cases |
| whitespace shares a memo entry | trimming |
| disk error never remembered | failure not cached |

✅ Strong, focused unit tests with a fake disk. They test the **resolver**, not whether the resolved URL is actually reachable.

### 2.4 `tests/Unit/OfficeMapCoordinatesTest.php` — 10 methods

Covers `OfficeMap::isValidPair`, `markers`, `embedUrl`, `directionsUrl`, `enabled`, zoom clamping, language sanitisation, and `Office::hasCoordinates`.

Notable: `test_the_embed_url_carries_no_api_key_and_no_third_party_host` (82) asserts the URL has no `key=` and no non-Google host. ✅ Good security regression test.

### 2.5 `tests/Feature/SettingsCacheTest.php` — 10 methods

The most sophisticated file. Covers:

- request-local snapshot isolation between tests
- successful read caches raw rows
- empty table cached as a successful read (`[]` vs failure)
- warm cache serves values
- failed DB read returns defaults **and poisons nothing**
- recovery after failure populates cache
- `set` / `setMany` invalidate the shared cache
- cached payload holds raw paths, not resolved URLs (no host baked in)
- unavailable cache store does not break reads or invalidation

✅ This is production-grade testing of a subtle caching layer. See [12 §9](12-site-settings.md#9-cache-behaviour-tests).

### 2.6 `tests/Feature/PageHeadingTest.php` — 1 method, 16 cases

`GET` each of 16 public routes, assert 200 and exactly one `<h1>` opening tag, counted with `preg_match_all('/<h1[\s>]/i')`.

✅ Catches duplicate/missing `<h1>`.
⚠️ Does **not** cover `/insights/{slug}` (needs a fixture), the RSS feed, or the 404 page.
⚠️ Uses `RefreshDatabase`, so it runs against an empty DB — every page renders its **fallback** content. It proves the fallbacks work, not the DB-backed paths.

### 2.7 `tests/Feature/WhatsappFloatTest.php` — 6 methods

| Test | Asserts |
|---|---|
| no number stored → no button | gate |
| switch off → button hidden, number retained | gate |
| button links to stored number | href |
| new tab + `rel="noopener noreferrer"` | security |
| button on every public page | 5-page provider (`/`, `/contact`, `/management`, `/gallery`, `/insights`) |
| number normalisation | 8-case provider (`+`, spaces, dashes, parens, `00`, empty, placeholder, short) |

✅ Excellent coverage of a small feature. `whatsappHref()` is genuinely well tested.

### 2.8 `tests/Feature/OfficeMapPageTest.php` — 16 methods

Covers rendering on homepage and contact, absence with no verified location, one map per verified office, no Google request before activation, directions link without JS, real `<button>` with `aria-describedby`, half-pairs rejected, out-of-range rejected, feature switch, unique ids for two instances, no Leaflet leftovers, and three CSP assertions (frame origin present, follows config, dropped when disabled).

✅ The CSP assertions (205-231) are a strong pattern — they lock the header to the config value.

⚠️ These render the Blade component with a supplied `$offices` array; they do not exercise the admin form that writes coordinates.

### 2.9 `tests/Feature/StaticAssetCacheHeadersTest.php` — 6 methods

Reads `public/.htaccess` as **text** and asserts directives are present/absent.

| Test | Asserts |
|---|---|
| hashed build assets immutable 1 year | directive text |
| replaceable assets 7 days, not immutable | directive text |
| Filament assets 30 days | directive text |
| every Cache-Control rule scoped with `env=` | regex over all rules |
| no `Header always set Cache-Control` | avoids caching errors |
| routing/rewrite rules preserved | 4 directive strings |

✅ Clever: it prevents a regression where someone adds an unscoped cache rule.

⚠️ **It proves the file's contents, not the served headers.** The test's own docblock says so (10-15). On Nginx these rules do nothing and the test still passes.

---

## 3. How to run

```bash
php artisan test
```

or

```bash
composer test
```

which runs `php artisan config:clear --ansi` then `php artisan test` (`composer.json:49-52`).

Useful variants:

```bash
php artisan test --filter=SettingsCache
php artisan test tests/Unit
php artisan test --parallel        # requires brianium/paratest — NOT installed
php artisan test --coverage        # requires xdebug or pcov
```

⚠️ `--parallel` will fail: `brianium/paratest` is not in `composer.json`.
⚠️ `--coverage` needs a driver; none is configured.

### 3.1 Test environment

`phpunit.xml:20-35` forces:

| Key | Value |
|---|---|
| `APP_ENV` | `testing` |
| `CACHE_STORE` | `array` |
| `DB_CONNECTION` | `sqlite` |
| `DB_DATABASE` | `:memory:` |
| `MAIL_MAILER` | `array` |
| `QUEUE_CONNECTION` | `sync` |
| `SESSION_DRIVER` | `array` |
| `BCRYPT_ROUNDS` | `4` |

✅ Tests cannot touch the dev database, cannot send mail, and cannot write sessions to the real table.

⚠️ `APP_ENV=testing` is not `production`, so `seo.blade.php` emits `noindex, nofollow` in tests. Any test asserting indexability would fail — none does.

---

## 4. Coverage map

| Area | Covered | Gap |
|---|---|---|
| `ImageUrl` | ✅ 8 unit tests | no HTTP-level check that URLs resolve |
| `Settings` cache | ✅ 10 feature tests | `CLEARABLE_KEYS` semantics untested |
| `OfficeMap` | ✅ 10 unit + 16 feature | admin coordinate entry untested |
| WhatsApp float | ✅ 6 tests, 17 cases | — |
| `<h1>` per page | ✅ 16 routes | detail page, 404 |
| `.htaccess` cache rules | ✅ 6 tests | served headers |
| **Contact form POST** | ⛔ **none** | validation, persistence, mail, throttle |
| **Insights detail** | ⛔ none | 404, unpublished, related, schema |
| **RSS feed** | ⛔ none | XML validity |
| **Admin panel** | ⛔ none | login, CRUD, uploads, authorization |
| **Redirects** | ⛔ none | `/index.php/*`, `/home/*` |
| **Security headers** | ⚠️ only CSP frame-src | full header set |
| **JSON-LD** | ⛔ none | parse/validity |
| **Sitemap command** | ⛔ none | generation |
| **Seeders** | ⛔ none | idempotency |
| **Consent gating** | ⛔ none | `analyticsEnabled()` matrix |
| **SEO metadata** | ⛔ none | title/description/canonical presence |
| **404 / 500 pages** | ⛔ none | — |
| **Authorization** | ⛔ nothing to test | no RBAC exists |

**Estimated line coverage: low.** Roughly half the `app/` surface is untouched — controllers, the contact pipeline, all Filament classes, the console command, and the middleware (except CSP).

---

## 5. Highest-value missing tests

Ordered by risk, not effort:

1. **`ContactController::store`** — success path writes a row and sends 2 mails (`Mail::fake`); validation errors; throttle returns 429; mail exception still returns success.
2. **`InsightController::show`** — 200 for published, 404 for draft, 404 for future date, related posts exclude self.
3. **`Consent::analyticsEnabled`** — 4-case matrix (env off, setting off, no id, all on).
4. **Admin authorization** — currently impossible; a test asserting "a second user cannot access X" would document the absence and catch a future regression.
5. **`GenerateSitemap`** — run the command, assert the XML contains each static path plus published posts and excludes drafts.
6. **Redirects** — `GET /index.php/home/history` → 301 `/company-profile`, query preserved.
7. **Security headers** — assert the full set on a public response and on `/admin`.
8. **Seeder idempotency** — currently fails; a test would pin the defect.
9. **JSON-LD** — `json_decode` the `<script type="application/ld+json">` block and assert `@graph` node types.

---

## 6. Quality observations

**Strengths:**

- Tests are focused and named in plain English.
- Unit tests use fakes (`Storage::fake`, cache fakes) rather than touching real infrastructure.
- `SettingsCacheTest` tests failure modes, not just happy paths — rare and valuable.
- `StaticAssetCacheHeadersTest` guards configuration text, which is unusual and effective.
- `OfficeMapPageTest` asserts CSP/iframe coupling, preventing drift.
- `phpunit.xml` isolates the test environment completely.

**Weaknesses:**

- Two placeholder `ExampleTest` files remain.
- No test touches the contact form — the only public write path.
- No admin tests at all.
- No browser/DOM tests, so JS behaviour is untested.
- No coverage threshold enforced.
- No CI, so tests only run when a developer remembers.
- `PageHeadingTest` runs against an empty database, so it validates fallbacks only.
- Several tests assert file contents rather than behaviour (`StaticAssetCacheHeadersTest`), which is a proxy metric.

---

## 7. Manual QA checklist

Not automated; recommended before a release.

### Public

- [ ] Every nav and footer link returns 200
- [ ] `/index.php/home/history?x=1` → 301 `/company-profile?x=1`
- [ ] `/home/hardService` and `/home/hardservice` both → `/hospitality-management`
- [ ] Trailing slash `/contact/` → 301 `/contact`
- [ ] `/insights/nonexistent` → 404 page
- [ ] Contact form: valid submit → success flash, row in admin, 2 emails
- [ ] Contact form: invalid submit → field errors
- [ ] Contact form: 11 rapid submits → 429
- [ ] Consent: accept → GA4 request appears in devtools
- [ ] Consent: decline → no request to Google
- [ ] Consent: decline after accept → `_ga` cookies cleared, page reloads
- [ ] Footer "Cookie settings" reopens the banner
- [ ] Office map: no map when coordinates are empty; directions link present
- [ ] WhatsApp float absent when no number is set

### Admin

- [ ] `/admin` redirects to login when logged out
- [ ] Login works; logout works
- [ ] Each of the 10 resources lists, creates, edits, deletes
- [ ] Image upload previews and saves
- [ ] Uploaded image is reachable at its `/storage/...` URL
- [ ] Settings save and persist across reload
- [ ] Test email arrives (or shows a clear error)
- [ ] Mark enquiry read/unread
- [ ] Dashboard counts match the lists

### Non-functional

- [ ] `curl -I` shows the security headers
- [ ] `/admin` carries `X-Robots-Tag: noindex`
- [ ] Staging is `noindex`
- [ ] No horizontal scroll at 320 px
- [ ] Keyboard-only pass through the homepage
- [ ] Screen reader pass on the contact form

---

## Known limitations

- **The suite was never executed.** Pass/fail status is unknown. Tests may fail on this machine for environment reasons unrelated to code.
- No coverage report exists.
- No CI history exists to indicate whether tests ever passed.
- The effective case count is inferred from data providers, not observed.
- Browser-based checks were not performed.

---

## Related chapters

- [03 — Technology Stack](03-technology-stack.md#5-testing-stack)
- [10 — Contact & Enquiries](10-contact-and-enquiries.md#10-testing-coverage)
- [11 — Admin Panel Reference](11-admin-panel-reference.md#12-testing-coverage)
- [12 — Site Settings](12-site-settings.md#9-cache-behaviour-tests)
- [17 — Security & Hardening](17-security-and-hardening.md)
- [20 — Deployment & Operations](20-deployment-and-operations.md)
- [21 — Known Issues](21-known-issues-decisions-and-roadmap.md)
