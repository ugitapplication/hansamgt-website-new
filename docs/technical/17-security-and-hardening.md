# 17 — Security & Hardening

> **Purpose:** Assess the application's actual security posture from source: headers, CSP, authentication, authorization, input handling, XSS trust boundaries, secrets, and abuse controls.
> **Audience:** Developers, security reviewers, and the site owner.
> **Last reviewed:** 2026-10-19
> **Evidence basis:** `app/Http/Middleware/SecurityHeaders.php` (122 lines, full), `app/Http/Middleware/RedirectIndexPhp.php`, `bootstrap/app.php`, `config/auth.php`, `app/Models/User.php`, all controllers, all Filament resources, `public/.htaccess`, `.env.example`, `.gitignore`. **No penetration test was performed.**

**Verification legend:** ✅ source-verified · 🟡 runtime-unverified · ⚠️ partial · ⛔ missing · ❓ unknown

> **No secret value appears in this chapter.** Variable names and safe instructions only.

---

## 1. Summary

| Control | Status | Severity if unaddressed |
|---|---|---|
| HTTPS/HSTS | ⚠️ HSTS only when already secure | Medium |
| Security headers | ✅ good baseline | — |
| CSP | ⚠️ `unsafe-inline` on public, `unsafe-eval` on admin | Medium |
| Authentication | ✅ Filament login | — |
| **Authorization** | ⛔ **none** | **High** |
| 2FA | ⛔ none | High (given no RBAC) |
| CSRF | ✅ framework + explicit `@csrf` | — |
| SQL injection | ✅ Eloquent/parameterised | — |
| XSS (public) | ⚠️ article body rendered raw | High |
| XSS (admin) | ✅ Blade-escaped + Filament | — |
| Mass assignment | ✅ `$fillable` everywhere | — |
| File upload | ✅ type/size validated; no SVG | — |
| Rate limiting | ⚠️ contact only (10/min) | Medium |
| Login rate limiting | ✅ Filament default | — |
| Secrets in repo | ✅ none found; `.env` ignored | — |
| Dependency audit | ❓ not run | Unknown |
| Audit logging | ⛔ none | Medium |
| Backups | ⛔ none in repo | High (operational) |
| Error disclosure | ⚠️ `APP_DEBUG` unknown; test-email leaks exception text | Medium |

---

## 2. Transport and headers

### 2.1 Middleware registration

`bootstrap/app.php:13-16` appends `RedirectIndexPhp` and `SecurityHeaders` to the **global** stack. They run on public pages, admin pages, and error responses alike. ✅

### 2.2 Headers set

`SecurityHeaders::handle` (11-53):

| Header | Value | Notes |
|---|---|---|
| `X-Content-Type-Options` | `nosniff` | |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | |
| `X-Frame-Options` | `SAMEORIGIN` | superseded by CSP `frame-ancestors` but harmless |
| `Permissions-Policy` | `geolocation=(), microphone=(), camera=(), payment=()` | |
| `Cross-Origin-Opener-Policy` | `same-origin` | |
| `X-Robots-Tag` | admin: `noindex, nofollow, noarchive`; ≥400: `noindex, nofollow` | |
| `Strict-Transport-Security` | `max-age=31536000; includeSubDomains` | **only when `$request->secure()`** (44-46) |
| `Content-Security-Policy` | see §3 | only if not already set |
| `X-Powered-By` | **removed** (17-20) | both header bag and raw `header_remove` |

Headers are set only if absent (30-34), so a reverse proxy can override them. ✅ Good practice.

### 2.3 HSTS caveat

HSTS is emitted **only when the request already arrived over HTTPS**. That is correct behaviour (never send HSTS over HTTP), but it means **the application does not force HTTPS**. A visitor who reaches `http://hansamgt.com` gets no redirect and no HSTS from this code. Enforcement must come from the host/proxy.

⚠️ **Unverified:** whether the host redirects HTTP→HTTPS. This is a deployment check, not a code defect. Recorded in [20 — Deployment](20-deployment-and-operations.md#7-tls-and-headers).

⛔ No `preload` directive on HSTS, and no `includeSubDomains` risk assessment was documented.

---

## 3. Content Security Policy

`SecurityHeaders::contentSecurityPolicy(bool $isAdmin)` (55-107).

### 3.1 Public site

| Directive | Value |
|---|---|
| `default-src` | `'self'` |
| `base-uri` | `'self'` |
| `object-src` | `'none'` |
| `frame-ancestors` | `'self'` |
| `frame-src` | `'self'` + Google Maps origin (only when maps enabled) |
| `form-action` | `'self'` |
| `img-src` | `'self' data: googletagmanager google-analytics` |
| `font-src` | `'self' data:` |
| `script-src` | `'self' 'unsafe-inline' googletagmanager google-analytics` |
| `style-src` | `'self' 'unsafe-inline'` |
| `connect-src` | `'self' google-analytics region1.google-analytics googletagmanager` |

### 3.2 Admin panel additions

- `script-src` gains `'unsafe-eval'` (Filament/Livewire/Alpine need it).
- `connect-src` gains `ws:` and `wss:` (Livewire websockets / dev).

### 3.3 Local development

When `app()->environment('local')`, `http://localhost:5173` and `http://127.0.0.1:5173` are added to `script-src`, `style-src`, `connect-src`, plus websocket origins (87-92).

⚠️ This is gated on the environment **name**, not on a config flag. If a production `.env` were mistakenly set to `APP_ENV=local`, the Vite dev origins would be allowed in the CSP. Low risk (they would be unreachable) but worth knowing.

### 3.4 Assessment

**Strengths:**

- `object-src 'none'` blocks plugin-based XSS.
- `base-uri 'self'` blocks `<base>` injection.
- `form-action 'self'` prevents form hijacking to an external origin.
- `frame-ancestors 'self'` prevents clickjacking.
- The Google Maps frame origin is **derived from the same config value** used to build the iframe URL (70-76), so the policy and the embed cannot drift. `OfficeMapPageTest` asserts this (205-231).
- No wildcard origins.

**Weaknesses:**

- `script-src 'unsafe-inline'` on the public site means any inline `<script>` executes. This is required by the consent bootstrap and GA4 snippet, but it removes CSP's primary XSS mitigation.
- `'unsafe-eval'` on admin is a Filament requirement, not a choice.
- No `nonce` or `hash` strategy is used. Adding nonces would allow removing `'unsafe-inline'` from `script-src` (though `style-src` would still need it for inline styles).
- No `report-uri` / `report-to`, so violations are invisible.

**Combined with the raw article body (§6), `unsafe-inline` turns a stored-XSS primitive into direct script execution.**

---

## 4. Authorization

This is the most serious finding in the codebase.

### 4.1 What exists

| Control | Evidence |
|---|---|
| Authentication | `AdminPanelProvider.php:29` `->login()`; `Filament\Http\Middleware\Authenticate` (61-63) |
| Session guard | `config/auth.php:40-45`, Eloquent provider, `App\Models\User` |
| Login throttling | Filament's built-in login rate limiter ⚠️ (framework behaviour, not customised) |

### 4.2 What does not exist

| Control | Evidence |
|---|---|
| Roles / permissions | no `role` column (`users` migration), no package, no enum |
| Policies | no `app/Policies` directory |
| Gates | `grep` for `Gate::` across `app/` → no matches |
| `authorize()` / `can()` | no matches in controllers or resources |
| `is_admin` flag | absent from `users` |
| User management UI | no `UserResource` |
| 2FA | no MFA configuration in the panel |
| IP allow-list | none |
| Audit log | none |
| Session invalidation on password change | `AuthenticateSession` middleware is present (`AdminPanelProvider.php:54`), which invalidates other sessions on password change ✅ |

### 4.3 Consequence

**Any row in `users` can do everything in `/admin`.** That includes:

- reading every enquiry (name, email, phone, message)
- deleting any content
- deleting enquiries
- changing the enquiry recipient (silently redirecting future leads)
- changing branding and analytics
- uploading arbitrary images
- writing arbitrary HTML into an article body (see §6)

There is no way to create a "content editor" who cannot see PII. There is no way to revoke one capability without revoking all.

### 4.4 How accounts are created

Only via:

- `DatabaseSeeder` (`test@example.com` / known default password — see §5)
- `UserFactory`
- `php artisan tinker`

There is no UI. Adding an editor requires shell access, which is a de-facto second layer of protection but not a designed control.

**Severity: High.** This is not a theoretical risk: the privacy policy claims access is limited (§7 of [16](16-analytics-consent-and-privacy.md#7-privacy-policy--accuracy-audit)).

**Mitigations available without code changes:**

1. Keep the `users` table to the minimum number of accounts.
2. Use long, unique passwords stored in a password manager.
3. Restrict `/admin` at the proxy/host by IP where staff IPs are stable.
4. Enable host-level WAF rules for `/admin/login`.
5. Review `users` after any staff change.

**Proper fix (not applied):** add a role column or a policy layer, or install a permission package, and gate `ContactSubmissionResource` and destructive actions.

---

## 5. Authentication details

### 5.1 Password handling

`app/Models/User.php:42-48` casts `password => hashed`, so `bcrypt`/`argon` hashing is automatic. `$hidden` excludes `password` and `remember_token` from serialisation. ✅

### 5.2 Email verification

`MustVerifyEmail` is commented out (`User.php:5`). Email addresses are never verified. ⚠️ Low impact for an admin-only panel, but it means a typo in an account email is undetected.

### 5.3 Seeded account

`database/seeders/DatabaseSeeder.php:20-23` creates:

```php
User::factory()->create([
    'name' => 'Test User',
    'email' => 'test@example.com',
]);
```

`UserFactory` uses a fixed default password (`password`). **If this seeder has ever run in production, a known-credential admin account exists.**

⚠️ **Unverified:** whether production ran the seeder. The `.env` and database were not read. This must be checked by the operator:

```bash
# read-only check
php artisan tinker --execute="echo App\Models\User::where('email','test@example.com')->exists() ? 'PRESENT' : 'absent';"
```

If present: delete it or rotate its password immediately.

### 5.4 No password reset

`AdminPanelProvider` never calls `->passwordReset()`. A locked-out admin must use CLI/tinker. This is inconvenient but not a vulnerability.

### 5.5 No registration

No `->registration()`. Public self-service account creation is impossible. ✅

---

## 6. XSS trust boundaries

### 6.1 The raw render

`resources/views/pages/insight.blade.php:27-29`:

```blade
<div class="article-body">
    {!! $post->body !!}
</div>
```

`{!! !!}` disables Blade escaping. The content originates from Filament's `RichEditor` (`PostResource.php:80-84`).

### 6.2 What Filament's RichEditor allows

RichEditor (TipTap-based) produces HTML. By default it permits headings, lists, links, bold/italic, and similar formatting. It is **not** a sanitiser: depending on configuration it can preserve raw HTML, and an admin who can call the API directly is not constrained by the toolbar.

### 6.3 Why this matters

| Layer | Protection |
|---|---|
| Blade | ⛔ bypassed by `{!! !!}` |
| CSP `script-src` | ⚠️ `'unsafe-inline'` — inline `<script>` executes |
| CSP `object-src 'none'` | ✅ blocks plugin payloads |
| CSP `form-action 'self'` | ✅ blocks exfiltration via form post |
| CSP `connect-src` | ⚠️ allows `google-analytics` and `googletagmanager` — an attacker could beacon data there via a crafted measurement request |

So a malicious or compromised admin can inject `<script>` that runs for every visitor of that article.

### 6.4 Threat model

The realistic threat is **not** an anonymous visitor — they cannot reach the editor. It is:

1. A compromised admin account (made worse by no 2FA and no RBAC).
2. A malicious insider with any admin access.
3. A future feature that lets lower-trust users write content.

Given §4, "any authenticated admin" is the entire trust boundary. There is no separation between "can write an article" and "can run script on every visitor's browser".

**Severity: High** in combination with §4. In isolation (trusted single admin), Medium.

### 6.5 Recommended fix (not applied)

- Sanitise on save with an allow-list HTML sanitiser (e.g. a HTMLPurifier integration), **or**
- Render with `{{ }}` if rich text is not actually needed, **or**
- Move to a nonce-based CSP and strip `<script>` from stored HTML.

---

## 7. Input handling

### 7.1 SQL injection

All queries use Eloquent query builder with bound parameters. No raw SQL string interpolation was found (`grep` for `DB::raw`, `whereRaw`, `selectRaw` in `app/` → none). ✅

### 7.2 Mass assignment

Every model defines `$fillable` explicitly. `ContactController` passes `$validated` (not `$request->all()`) to `create()`. ✅

### 7.3 CSRF

- Framework `VerifyCsrfToken` in the web group and in the Filament middleware stack.
- The contact form includes `@csrf`.
- `seo.blade.php:175` emits a `csrf-token` meta tag (used by nothing currently, since there is no AJAX).
- Legacy redirects explicitly skip POST (`RedirectIndexPhp.php:33-36`, `.htaccess:20-21`). ✅

### 7.4 Validation

Only the contact form validates at the HTTP layer (`ContactController.php:22-36`). Filament validates every resource field. There are no Form Requests. ✅ adequate for the surface area.

### 7.5 File upload

| Control | Status |
|---|---|
| MIME allow-list | ✅ jpeg/png/webp only |
| Size cap | ✅ 2-3 MB |
| SVG rejected | ✅ |
| Stored outside webroot | ✅ `storage/app/public` (once symlinked) |
| Filename sanitised | ✅ Filament generates hashed names |
| Executable upload | ⛔ no `.php` possible via the allow-list |
| Image re-encoding | ⛔ none — a polyglot file could carry extra bytes, but the allow-list and `image()` validation reduce this |

⚠️ `image()` validation in Filament checks the MIME type via the `fileinfo` extension. On a host with a broken `fileinfo`, this could be weaker. ❓ host-dependent.

---

## 8. Abuse controls

| Surface | Control |
|---|---|
| `POST /contact` | `throttle:10,1` (10/min/IP) |
| `/admin/login` | Filament default throttle ⚠️ not customised |
| `/insights/feed` | ⛔ none — cheap query, low risk |
| Search | none public |
| Uploads | admin-only |

⚠️ Contact form gaps:

- No honeypot, no CAPTCHA, no proof-of-work.
- No per-email or per-phone limit.
- IP-based throttle is bypassable by distributed senders.
- Every submission triggers **two** outbound emails, so the form is an amplification vector against the SMTP provider's reputation.

**Severity: Medium.** The form is the only public write endpoint, and it writes PII and sends mail.

---

## 9. Secrets and repository hygiene

| Check | Result |
|---|---|
| `.env` tracked? | ⛔ no — `.gitignore:4` |
| `.env.example` contains real secrets? | ⛔ no — placeholders only |
| `APP_KEY` committed? | ⛔ no |
| Mail credentials in repo? | ⛔ no |
| Legacy Google Maps key | ⚠️ **present in `hansamgtWebsite_old/`** — the directory is git-ignored (`.gitignore:26`) but exists on disk. The key must be treated as compromised and revoked. **Not reproduced here.** |
| API keys in JS? | ⛔ none — the map is a keyless embed |
| Debug mode | ❓ `.env.example:4` sets `APP_DEBUG=true`; production value unknown |
| `composer.lock` / `package-lock.json` committed | ✅ yes (expected) |
| Dependency vulnerability scan | ❓ `composer audit` / `npm audit` not run |

⚠️ **Action required:** revoke the legacy Google Maps API key in the Google Cloud console, regardless of whether the old directory is deployed. It was committed to a repository at some point and must be considered public.

---

## 10. Error handling and information disclosure

| Surface | Behaviour |
|---|---|
| Public page DB failure | swallowed by `try/catch`, renders fallback content (no error) |
| Insight detail DB failure | returns 404 (masks the outage) |
| Contact mail failure | `report()` to log; visitor sees success |
| Admin test-email failure | **exception message (300 chars) shown in the admin UI** (`ManageSettings.php:356-362`) — may include SMTP host/username |
| `APP_DEBUG=true` in production | would render stack traces with env values to the public |
| 404/500 pages | custom Blade views, no stack trace when debug is off |

⚠️ The test-email disclosure is acceptable for a trusted operator but is visible to **every** admin (no roles). It should not be pasted into tickets.

⚠️ `APP_DEBUG` in production is **unverified** and is the single highest-impact unknown: a debug-enabled production site leaks environment variables, including database and mail credentials, on any exception.

---

## 11. Privacy-adjacent security

| Issue | Detail |
|---|---|
| Enquiry PII readable by all admins | §4 |
| No audit trail of who read/deleted an enquiry | no logging |
| Enquiry data in mail logs | `report($exception)` may include recipient |
| No data export/erasure tooling | manual SQL/tinker only |
| No retention enforcement | [16 §6](16-analytics-consent-and-privacy.md#6-retention--the-central-gap) |
| Database file on disk unencrypted | SQLite; host-level disk encryption unknown ❓ |

---

## 12. Hardening checklist

### Before launch (blocking)

- [ ] Confirm `APP_DEBUG=false` and `APP_ENV=production` on the live host.
- [ ] Confirm the seeded `test@example.com` account is absent or rotated.
- [ ] Set a real `MAIL_FROM_ADDRESS` on the `hansamgt.com` domain (SPF/DKIM/DMARC aligned).
- [ ] Run `php artisan storage:link`.
- [ ] Revoke the legacy Google Maps key.
- [ ] Confirm HTTP→HTTPS redirect at the host.

### Short term

- [ ] Add a role/permission layer or install a permission package.
- [ ] Add 2FA for admin accounts.
- [ ] Sanitise or escape article HTML.
- [ ] Add a contact-form honeypot/CAPTCHA.
- [ ] Add a retention/pruning command.
- [ ] Restrict `/admin` by IP at the proxy if feasible.
- [ ] Configure `LOG_CHANNEL=daily` with a bounded `LOG_DAILY_DAYS`.

### Medium term

- [ ] Nonce-based CSP to remove `'unsafe-inline'` from `script-src`.
- [ ] CSP reporting endpoint.
- [ ] Audit log for admin actions.
- [ ] Automated dependency scanning in CI.
- [ ] Backup + restore drill.

---

## Known limitations

- No penetration test, scanner, or manual exploit was performed.
- Live headers were not observed; all header claims are from source.
- `APP_DEBUG`, `APP_ENV`, and the live `users` table are unknown.
- `composer audit` / `npm audit` could not run (shell unavailable).
- Filament's built-in login throttling behaviour was not verified in this version.
- The actual RichEditor HTML allow-list was not inspected in `vendor/`.
- Host-level controls (WAF, fail2ban, IP allow-listing, TLS configuration) are outside the repository.

---

## Related chapters

- [05 — Configuration Reference](05-configuration-reference.md)
- [10 — Contact & Enquiries](10-contact-and-enquiries.md)
- [11 — Admin Panel Reference](11-admin-panel-reference.md#2-authorization-reality)
- [12 — Site Settings](12-site-settings.md#6-send-test-email)
- [16 — Analytics, Consent & Privacy](16-analytics-consent-and-privacy.md)
- [19 — Testing & QA](19-testing-and-qa.md)
- [20 — Deployment & Operations](20-deployment-and-operations.md)
- [21 — Known Issues](21-known-issues-decisions-and-roadmap.md)
