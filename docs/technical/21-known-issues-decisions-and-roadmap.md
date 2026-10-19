# 21 — Known Issues, Decisions & Roadmap

> **Purpose:** A single, severity-ordered register of confirmed defects, unresolved risks, deliberate design decisions, and deferred work — each tied to evidence.
> **Audience:** Site owner, developers, and anyone planning the next iteration.
> **Last reviewed:** 2026-10-19
> **Evidence basis:** Direct source inspection across all chapters. **Nothing in this chapter has been fixed.** No application file was modified.

**Verification legend:** ✅ source-confirmed · 🟡 source-inferred / runtime-unverified · ⚠️ partial · ⛔ missing · ❓ unknown

> This chapter separates **confirmed source findings** (the code says X) from **runtime-unverified conclusions** (X probably causes Y on the live host). Do not treat the second group as measured fact.

---

## 1. Launch blockers

These should be resolved before the site is considered production-ready.

### B1 — Missing `public/storage` symlink

| Field | Detail |
|---|---|
| Severity | **High** |
| Type | ✅ confirmed source + filesystem; 🟡 impact inferred |
| Evidence | `public/storage` absent; `.gitignore:22`; `composer.json:37-44` has no `storage:link`; uploads exist under `storage/app/public/{team,hero-slides}` |
| Effect | Every admin-uploaded image resolves to `/storage/...` and 404s (`ImageUrl.php:60-61`) |
| Blast radius | Hero, team, clients, concerns, projects, gallery, post covers, branding |
| Fix | `php artisan storage:link` on the server; add it to the deploy script |
| Caveat | Production may already have the link — unverified |

### B2 — Unverified production mail transport and sender

| Field | Detail |
|---|---|
| Severity | **High** |
| Type | ✅ confirmed in `.env.example`; ❓ live `.env` unknown |
| Evidence | `MAIL_MAILER=log` (`.env.example:50`), `MAIL_FROM_ADDRESS="hello@example.com"` (`.env.example:56`), `config/mail.php:17` |
| Effect | With `log`, enquiries are written to `storage/logs/laravel.log` and **never delivered**, while the visitor sees success (`ContactController.php:60-62`) |
| Fix | Set a real SMTP transport and a `@hansamgt.com` sender with SPF/DKIM/DMARC aligned; send a test from **Site Settings → Send test email** |
| Note | Existing documents flag this as unresolved (defect 6). It is still unresolved in the repository. |

### B3 — No admin authorization model

| Field | Detail |
|---|---|
| Severity | **High** |
| Type | ✅ confirmed source |
| Evidence | No `app/Policies`; no `Gate::`; no `authorize()`/`can()`; no `role` column (`0001_01_01_000000_create_users_table.php:14-22`); `AdminPanelProvider.php:29` enables login only |
| Effect | Any row in `users` can read all enquiry PII, delete any content, and change the enquiry recipient. The privacy policy's claim that access is limited is **false** (`privacy.blade.php:211`) |
| Fix | Add roles/policies or a permission package; gate `ContactSubmissionResource` and destructive actions; add 2FA |
| Interim | Minimise accounts, strong passwords, IP-restrict `/admin` at the proxy |

### B4 — Seeded default admin account

| Field | Detail |
|---|---|
| Severity | **High if present in production** |
| Type | ✅ confirmed in seeder; ❓ whether it ran |
| Evidence | `DatabaseSeeder.php:20-23` creates `Test User` / `test@example.com` unconditionally; `UserFactory` uses a known default password |
| Effect | Known-credential admin access |
| Fix | Check and delete/rotate; make the seeder conditional |
| Note | Not a launch blocker if production was never seeded — verify first |

---

## 2. Medium severity

### M1 — Article body rendered unescaped

| Field | Detail |
|---|---|
| Severity | **Medium–High** |
| Type | ✅ confirmed source |
| Evidence | `pages/insight.blade.php:28` `{!! $post->body !!}`; CSP allows `'unsafe-inline'` (`SecurityHeaders.php:57`) |
| Effect | Any admin can execute script for every visitor of an article |
| Aggravated by | B3 (no RBAC) — the trust boundary is "any authenticated admin" |
| Fix | Server-side HTML sanitisation with an allow-list, or nonce-based CSP + script stripping |

### M2 — Static sitemap, no scheduled regeneration

| Field | Detail |
|---|---|
| Severity | **Medium** |
| Type | ✅ confirmed source |
| Evidence | `GenerateSitemap.php` exists; `routes/console.php` registers nothing; no `Schedule::` call anywhere; `public/sitemap.xml` has 20 URLs, all `lastmod 2026-10-17` |
| Effect | Publishing an article does not update the sitemap |
| Fix | Register `Schedule::command('sitemap:generate')->daily()` and add the cron entry |

### M3 — Contact form spam and qualification gaps

| Field | Detail |
|---|---|
| Severity | **Medium** |
| Type | ✅ confirmed source |
| Evidence | `ContactController.php:22-36` — only name/email/phone/message; no honeypot, CAPTCHA, or consent checkbox; `throttle:10,1` is the only control |
| Effect | Spam reaches the inbox and the database; enquiries cannot be routed by service/location |
| Fix | Honeypot + CAPTCHA; add company/service/property/location fields (existing defect 5) |

### M4 — No retention enforcement

| Field | Detail |
|---|---|
| Severity | **Medium (compliance)** |
| Type | ✅ confirmed source |
| Evidence | Policy states 24 months (`privacy.blade.php:14,187`); no expiry column, no command, no schedule |
| Effect | Enquiry PII persists indefinitely; the published policy is inaccurate |
| Fix | Add `contact:prune` + schedule, or amend the policy |

### M5 — Non-idempotent database seeder

| Field | Detail |
|---|---|
| Severity | **Medium** |
| Type | ✅ confirmed source |
| Evidence | `DatabaseSeeder.php:20-23` `User::factory()->create(...)`; `users.email` is unique |
| Effect | A second `db:seed` aborts before content seeders |
| Fix | `firstOrCreate` / `updateOrCreate`, or gate the user block |

### M6 — `APP_DEBUG` / `APP_ENV` unverified in production

| Field | Detail |
|---|---|
| Severity | **Medium–High if misconfigured** |
| Type | ❓ unverified |
| Evidence | `.env.example:2,4` ships `APP_ENV=local`, `APP_DEBUG=true` |
| Effect | Debug mode leaks env values and stack traces to the public; non-production `APP_ENV` makes every page `noindex` |
| Fix | Confirm `APP_ENV=production`, `APP_DEBUG=false` |

### M7 — Test-email action discloses exception text

| Field | Detail |
|---|---|
| Severity | **Medium** |
| Type | ✅ confirmed source |
| Evidence | `ManageSettings.php:356-362` shows `Str::limit($exception->getMessage(), 300)` |
| Effect | SMTP host/username may be exposed to every admin (no roles) |
| Fix | Show a generic message; log the detail |

### M8 — CSP allows `unsafe-inline` on the public site

| Field | Detail |
|---|---|
| Severity | **Medium** |
| Type | ✅ confirmed source |
| Evidence | `SecurityHeaders.php:57` |
| Effect | Removes CSP as an XSS mitigation; directly enables M1 |
| Fix | Nonce-based `script-src`; keep `style-src` inline if needed |

### M9 — No backup, log rotation, or error tracking

| Field | Detail |
|---|---|
| Severity | **Medium** |
| Type | ✅ confirmed absence |
| Evidence | No backup command/package; `LOG_CHANNEL=stack` → `single`; no Sentry/Flare |
| Effect | Data loss risk; unbounded log growth; silent failures |
| Fix | Daily DB + uploads backup with a restore drill; `daily` log channel with `days`; an error tracker |

### M10 — Silent failure masking

| Field | Detail |
|---|---|
| Severity | **Medium** |
| Type | ✅ confirmed source |
| Evidence | Every public DB read is `try/catch (\Throwable)` with a Blade fallback (`HomeController`, `InsightController`) |
| Effect | The site returns 200 during a database outage; uptime monitors see nothing |
| Fix | Log the exception (currently swallowed without `report()` in most paths); add a health check that queries the DB |

---

## 3. Low severity

| # | Issue | Evidence | Effect |
|---|---|---|---|
| L1 | `og:image:alt` always says "… logo" | `seo.blade.php:188` | wrong alt on article shares |
| L2 | No `og:image:width/height` | `seo.blade.php:187` | weaker social previews |
| L3 | Fallback canonical is the homepage | `seo.blade.php:18` | a page omitting `canonical` declares the homepage canonical |
| L4 | `ItemList` positions restart per page | `InsightController.php:122-141` | minor schema inaccuracy |
| L5 | `articleSection` is always "Insights" | `InsightController.php:159` | no real taxonomy |
| L6 | "Related" = newest, not similar | `InsightController.php:105-117` | misleading label |
| L7 | Gallery image nullable → empty `src` | migration + form | broken image element |
| L8 | `fetchFileInformation(false)` hides size/MIME | all resources | admin shows `0` bytes |
| L9 | `axios` imported but unused | `bootstrap.js`, `app.js` | dead weight |
| L10 | Two placeholder `ExampleTest` files | `tests/` | noise |
| L11 | `welcome.blade.php`, `placeholder.blade.php` unrouted | `resources/views/` | dead files |
| L12 | Legacy icon fonts for 4 icons | `_services.blade.php` | extra requests |
| L13 | `settings.latitude/longitude` are free text | `ManageSettings.php:257-262` | non-numeric → `(float) 0.0` in schema |
| L14 | `route:cache` impossible due to closure routes | `routes/web.php:46,53` | deploy step must omit it |
| L15 | Duplicate `GA4_ENABLED` config source | `config/site.php:78`, `config/analytics.php:18` | can diverge |
| L16 | No `noscript` fallback | all views | content may be invisible without JS |
| L17 | Auto-advancing carousels without pause control | `app.js`, CSS | WCAG 2.2.2 |
| L18 | Placeholder-as-label on contact form | `_contact-form.blade.php` | WCAG 3.3.2 |
| L19 | No image processing / `srcset` | uploads | large payloads |
| L20 | Orphaned uploads on delete | no cleanup hook | disk growth |
| L21 | Legacy Google Maps key in `hansamgtWebsite_old/` | git-ignored directory | **must be revoked** |

---

## 4. Runtime-unverified conclusions

These are inferences, clearly separated from confirmed findings.

| # | Conclusion | Why unverified | How to verify |
|---|---|---|---|
| U1 | Uploaded images currently 404 | no HTTP request made | `curl -I https://hansamgt.com/storage/<path>` |
| U2 | Contact email is not delivered | live `.env` not read | admin "Send test email"; check the recipient inbox |
| U3 | Production may be `APP_DEBUG=true` | `.env` not read | `curl -s https://hansamgt.com/ | grep -i "whoops\|laravel"`; check `php artisan about` |
| U4 | `test@example.com` may exist | DB not opened | `php artisan tinker` existence check |
| U5 | Sitemap may be staler than the DB | DB not opened | compare `/sitemap.xml` with `/insights` |
| U6 | Cache headers may be absent | host unknown | `curl -I` on a `/build/` asset |
| U7 | HTTP→HTTPS may not redirect | host unknown | `curl -I http://hansamgt.com` |
| U8 | Session cookie may be named `laravel-session` | `APP_NAME` unknown | inspect cookies in devtools |
| U9 | Database may hold more/fewer rows than the seeders | DB not opened | admin list counts |
| U10 | Test suite may not pass | never executed | `php artisan test` |
| U11 | JS-dependent content may be invisible without JS | not rendered | disable JS and reload |
| U12 | Layout may overflow at 320 px | not rendered | responsive check |

---

## 5. Deliberate decisions (not defects)

Recording these prevents a future reader from "fixing" something intentional.

| Decision | Rationale | Evidence |
|---|---|---|
| Two image generations (`public/images` + `storage/app/public`) | legacy assets are numerous and hand-cropped; uploads are new | `ImageUrl.php:7-16` |
| Every DB read falls back to static content | the site must never show an error to a visitor | controller docblocks, Blade fallbacks |
| No FK constraints | content tables are independent; no relational integrity to enforce | all migrations |
| Future `published_at` hides a post without a scheduler | passive scheduling needs no cron | `Post::scopePublished` |
| Map renders nothing without verified coordinates | "no map" beats "map of a guess" | `OfficeMap.php:5-21` |
| Google Maps is a keyless embed, not the JS API | no API key, no billing account, no secret in the repo | `config/site.php:104-113` |
| Consent banner is a non-modal `region` | it must not block the page | `consent.blade.php:7-13` |
| Consent stored in `localStorage`, not a cookie | the record of consent should not itself need consent | `consent.js:8-11` |
| `Header set` not `Header always set` | never cache 4xx/5xx | `.htaccess:63-65` |
| Legacy controller method names retained | they mirror `RedirectIndexPhp::LEGACY_MAP` | `routes/web.php` + middleware |
| Settings cache key versioned by hand (`v1`) | explicit control over payload-shape changes | `Settings.php:53` |
| Failed settings read is never cached | a transient outage must not pin defaults for a day | `Settings.php:110-113` |
| `CLEARABLE_KEYS` excludes image keys | clearing an upload should restore the bundled logo | `Settings.php:18-44` |
| No SVG uploads | SVG can carry script | all `FileUpload` definitions |
| `unsafe-eval` only on admin | Filament/Livewire need it; the public site stays stricter | `SecurityHeaders.php:78-84` |

---

## 6. Stale existing documentation

The repository contains five pre-existing documents under `docs/`. They are **historical records**, not current specifications. The `docs/technical/` set is authoritative for the inspected implementation.

| Document | Stale claim | Contradicted by | Status |
|---|---|---|---|
| `website-audit-and-benchmark.md:229` | "`sitemap.xml` (14 URLs)" | `public/sitemap.xml` has **20** URLs | outdated |
| `website-audit-and-benchmark.md:230` | "no `resources/views/errors/` directory" | `errors/404.blade.php` and `errors/500.blade.php` **exist** | outdated |
| `website-audit-and-benchmark.md:288` | retention values "confirmed by the business" | no retention mechanism exists in code | claim stands as a business decision; implementation absent |
| `admin-panel-audit.md:277` | "Contact Submissions sits outside the navigation groups" | `ContactSubmissionResource.php:26` sets `Operations` | outdated (the same doc marks it fixed at line 309) |
| `admin-panel-audit.md:275-276` | "`MAIL_FROM_ADDRESS` is still `hello@example.com` in `.env`" | still true in `.env.example:56`; live value unknown | **still open** |
| `admin-panel-audit.md:263-269` | office phone/email duplication defects | fixed — `contact.blade.php` now guards each field | outdated |
| `HANSA-website-migration-report.md:27` | "Filament v4.15.2" | exact version unverified (`composer.lock` not read) | unverified |
| `HANSA-website-migration-report.md:161` | "Seeders … are idempotent" | the nine content seeders are; `DatabaseSeeder.php:20-23` is **not** | **partly incorrect** |
| `HANSA-website-migration-report.md:179` | "Requirements: … MySQL" | default is SQLite (`config/database.php:20`) | outdated |
| `2026-10-15-work-report.md:4` | "Filament v4.15.2" | unverified | unverified |

**Do not delete these documents.** They record the migration history and business decisions. Where they conflict with source, the source wins, and this chapter records the conflict.

---

## 7. Roadmap

Ordered by risk reduction per unit of effort. **Nothing here is implemented.**

### Phase A — Before launch (days)

1. Confirm `APP_ENV=production`, `APP_DEBUG=false`.
2. Set real mail transport + `MAIL_FROM_ADDRESS`; send a test.
3. Run `php artisan storage:link`; verify an uploaded image loads.
4. Check/remove `test@example.com`; rotate if present.
5. Revoke the legacy Google Maps key.
6. Confirm HTTP→HTTPS redirect and `/.env` is not reachable.
7. Confirm `SESSION_SECURE_COOKIE=true`.
8. Take a database backup before any further change.

### Phase B — Security and compliance (1–2 weeks)

9. Add roles/policies; gate enquiries and destructive actions.
10. Add 2FA for admin accounts.
11. Sanitise article HTML on save.
12. Add a contact honeypot/CAPTCHA and qualifying fields.
13. Add `contact:prune` + schedule; align the policy.
14. Add a daily log channel with a bounded retention.
15. Fix the seeder to be idempotent.
16. Register `sitemap:generate` on a schedule.
17. Remove or qualify the privacy policy's "limiting which colleagues can see enquiries" claim.

### Phase C — Quality and operations (2–4 weeks)

18. Add feature tests: contact POST, insight detail/404, consent matrix, redirects, headers, sitemap.
19. Add CI running `php artisan test` and `./vendor/bin/pint --test`.
20. Add a backup + restore drill; document RTO/RPO.
21. Add uptime monitoring that asserts dynamic content, not just 200.
22. Add an error tracker.
23. Nonce-based CSP; drop `unsafe-inline` from `script-src`.
24. Add `og:image:width/height`; fix `og:image:alt`.
25. Replace legacy icon fonts with inline SVG; drop axios.

### Phase D — Product (backlog)

26. Admin-editable copy for the static pages and service pages.
27. Job listings + `JobPosting` schema for careers.
28. Image processing (resize, WebP, `srcset`).
29. Audit log for admin actions.
30. Post categories/tags and genuinely related articles.
31. Preview unpublished posts as a visitor.
32. Bulk edit and drag-reorder on Homepage Sections.

---

## 8. Severity summary

| Severity | Count | Items |
|---|---|---|
| **High (launch blockers)** | 4 | B1 symlink, B2 mail, B3 authorization, B4 seeded account |
| **Medium** | 10 | M1–M10 |
| **Low** | 21 | L1–L21 |
| **Runtime-unverified** | 12 | U1–U12 |
| **Deliberate decisions** | 15 | §5 |
| **Stale doc claims** | 10 | §6 |

---

## 9. What is actually healthy

A balanced register matters. These parts are genuinely well built:

| Area | Why |
|---|---|
| Settings cache layer | failure-aware, tested, versioned key, no cache poisoning |
| `ImageUrl` resolver | memoised, tested, handles both image generations |
| Office map | keyless, no invented coordinates, CSP coupled to config, 16 tests |
| WhatsApp float | two independent gates, normalisation tested with 8 cases |
| Consent flow | prior consent, no pre-consent requests, revocable, `localStorage` |
| Legacy redirects | single-hop, query-preserving, three layers (middleware, routes, `.htaccess`) |
| Security headers | solid baseline, proxy-overridable, `X-Powered-By` stripped |
| Static asset caching | scoped, tested, correct use of `Header set` |
| Privacy policy text | detailed and mostly accurate (retention and access-control claims excepted) |
| Code documentation | unusually high-quality inline docblocks explaining *why*, not *what* |

---

## Known limitations of this register

- Severity is assigned by impact × likelihood as judged from source. No CVSS scoring, no exploit attempt.
- Runtime-unverified items (§4) are explicitly not findings — they are checks to perform.
- The live `.env`, database, host, and rendered UI were not inspected.
- `composer audit` / `npm audit` were not run, so dependency vulnerabilities are unknown.
- No penetration test was performed.
- Effort estimates in §7 are indicative, not commitments.

---

## Related chapters

- [17 — Security & Hardening](17-security-and-hardening.md)
- [16 — Analytics, Consent & Privacy](16-analytics-consent-and-privacy.md)
- [14 — Media & Image Pipeline](14-media-and-image-pipeline.md)
- [15 — SEO & Structured Data](15-seo-and-structured-data.md)
- [19 — Testing & QA](19-testing-and-qa.md)
- [20 — Deployment & Operations](20-deployment-and-operations.md)
