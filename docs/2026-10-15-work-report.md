# HANSA MGT Website — Development Work Report

**Date:** 15 October 2026
**Project:** hansamgt-website-new (Laravel 12 + Filament v4.15.2)
**Prepared by:** ____________________
**Reviewed by:** ____________________

---

## 1. Purpose

This document records the development work carried out on 15 October 2026:
frontend defect fixes on the public website, SEO and redirect restoration
from the legacy CodeIgniter site, and the first phase of the Filament admin
panel (homepage content management and contact enquiry handling).

---

## 2. Summary of Work

| # | Area | Status |
|---|------|--------|
| 1 | Contact page horizontal scrollbar | Fixed |
| 2 | Contact card social icon hover | Fixed |
| 3 | Legacy vs new site audit | Completed, reported |
| 4 | sitemap.xml and robots.txt | Added |
| 5 | GA4 tracking + Organization JSON-LD | Added |
| 6 | Legacy URL 301 redirects (old controller URLs, index.php variants) | Implemented |
| 7 | Blade ParseError on SEO partial (`@context`) | Fixed |
| 8 | `/index.php` redirect loop on local server | Fixed |
| 9 | Admin: Hero Slider management | Implemented |
| 10 | Contact form backend + admin Submissions module | Implemented |
| 11 | Admin: Valued Clients, Business Concerns modules | Implemented |
| 12 | Image upload fault diagnosis (APP_URL, storage link) | Diagnosed, corrected |

Items 9–11 require database migration, seeding and browser verification
by the site owner (Section 6).

---

## 3. Public Website Fixes

### 3.1 Contact page horizontal scrollbar
- **Cause:** `.contact-strip` used `margin: 0 calc(50% - 50vw)`, which extends
  past the viewport on Windows where `100vw` includes the scrollbar width.
- **Change:** `resources/css/app.css` — strip set to normal full-width flow;
  grid columns given `min-width: 0`; inputs capped with `max-width: 100%`;
  global `overflow-x: clip` added on `html, body` as a guard.

### 3.2 Contact card social hover
- **Cause:** Footer icons carry brand-colour hover classes; the contact card
  icons had no `:hover` rule at all.
- **Change:** `resources/css/app.css` — Facebook `#1877F2`, Twitter `#1DA1F2`,
  LinkedIn `#0A66C2` hover states with transition, matching footer behaviour.

---

## 4. SEO and Redirects

### 4.1 Crawler files
- `public/sitemap.xml` — all 13 canonical URLs (matches controller canonicals).
- `public/robots.txt` — `Allow: /` plus `Sitemap:` declaration.

### 4.2 Analytics and structured data
- `resources/views/partials/seo.blade.php` — GA4 tag (`G-NJDKQTDFB5`) and the
  Organization JSON-LD (name, Borak Mehnur address, +8801708800870,
  info@hansamgt.com, parent Unique Group, Facebook/LinkedIn profiles),
  ported from the legacy header. Logo path updated to the new asset location.

### 4.3 Legacy URL redirects
Old controller URLs (`home/history`, `home/hardService`, …) and `index.php`
variants now return 301 to the clean slugs, with query strings preserved.
POST requests are never redirected (legacy parity).
- `routes/web.php` — GET-only legacy routes, single shared map.
- `public/.htaccess` — `index.php/*` rules for Apache production.
- `app/Http/Middleware/RedirectIndexPhp.php` — server-independent handling
  (covers `php artisan serve` and Nginx, which ignore `.htaccess`).
  Redirects are built from scheme + host so the URL generator's `/index.php`
  base path cannot cause a redirect loop (found and fixed during testing).

### 4.4 Blade `@context` ParseError
The JSON-LD `"@context"` key was compiled by Laravel 12 as the `@context`
Blade directive, leaving an unclosed `if` block (500 on all pages). Fixed by
escaping to `"@@context"`, which renders the correct literal output.

---

## 5. Admin Panel (Filament)

Panel: `/admin`, existing login retained. Dashboard stays top-level.
New sidebar group **Homepage Management** currently holds Hero Slider,
Valued Clients and Business Concerns. Contact Submissions is a separate
top-level item. No placeholder links were added; remaining sections will
appear only when implemented.

### 5.1 Hero Slider
- Table `hero_slides` (label, image, alt text, sort order, active flag).
- Full create/list/edit/delete with drag-to-reorder and inline active toggle.
- Uploads: JPG/PNG/WebP, max 2 MB, stored on the `public` disk.
- Homepage uses managed slides automatically; the original 7 slides remain
  as fallback (including seeded records), so the page cannot break.

### 5.2 Contact enquiries
- Table `contact_submissions`; both public forms validate, store, and show
  inline errors with input preserved. Throttled (10/min).
- On submit: admin notification to info@hansamgt.com (with reply-to) plus
  visitor confirmation. Mail failures are logged only — the enquiry is
  never lost. Note: mail driver is still `log` (Section 6.3).

### 5.3 Valued Clients / Business Concerns
- Tables `clients`, `concerns`; same proven pattern (logo, alt, sort, active).
- Seeded with the live site's 18 client and 13 concern logos; no content invented.
- Shared `App\Models\Concerns\ResolvesImageUrl` trait resolves storage
  uploads and legacy `public/` images uniformly.

---

## 6. Pending Actions (Site Owner)

1. `php artisan migrate` — creates `hero_slides`, `clients`, `concerns`, `contact_submissions`.
2. `php artisan storage:link` — required, otherwise uploaded images return 404.
3. `php artisan db:seed --class=HeroSlideSeeder --class=ClientSeeder --class=ConcernSeeder`
   (seeders are idempotent; admin active/inactive choices are preserved on re-run).
4. Restart `php artisan serve` after any `.env`/config change; hard-refresh the browser (301s cache aggressively — verify with `curl -I` or incognito).
5. Browser checks: admin CRUD/reorder/deactivate for all three modules; homepage reflects order and hides inactive items; homepage with zero active rows falls back cleanly; one valid and one invalid contact submission from each form.
6. Production `.env`: `APP_URL=https://hansamgt.com` and real SMTP credentials;
   local `.env` currently points at `http://127.0.0.1:8000` to match `serve`.
7. Delete the test enquiry row(s) and any draft hero/client records before handover.

---

## 7. Known Limitations and Notes

- Shell access in the build environment was unavailable, so migrations,
  seeders and browser flows were verified by code review against the
  installed vendor sources only — not by execution. Section 6 covers
  what must still be run.
- The contact page "backend pending" notice was removed; the form is live.
- Remaining planned work (not started): About, Featured Services, Company
  Statistics, Featured Projects, Latest News and CTA admin modules;
  Services/Projects/News/Gallery/Site Settings modules; scroll-to-top
  button; footer link review; dead social links (`#` placeholders);
  homepage map decision (omitted by design, pending confirmation).

---

## 8. Files Changed

```
public/sitemap.xml                                        (new)
public/robots.txt                                         (modified)
public/.htaccess                                          (modified)
resources/css/app.css                                     (modified)
resources/views/partials/seo.blade.php                    (modified)
resources/views/pages/home.blade.php                      (modified)
resources/views/pages/contact.blade.php                   (modified)
resources/views/pages/home/sections/_hero.blade.php       (modified)
resources/views/pages/home/sections/_clients.blade.php    (modified)
resources/views/pages/home/sections/_concerns.blade.php   (modified)
resources/views/pages/home/sections/_contact.blade.php    (modified)
resources/views/emails/contact-admin.blade.php            (new)
resources/views/emails/contact-confirmation.blade.php     (new)
routes/web.php                                            (modified)
bootstrap/app.php                                         (modified)
app/Http/Middleware/RedirectIndexPhp.php                  (new)
app/Http/Controllers/ContactController.php                (new)
app/Http/Controllers/HomeController.php                   (modified)
app/Models/HeroSlide.php                                  (new)
app/Models/Client.php                                     (new)
app/Models/Concern.php                                    (new)
app/Models/ContactSubmission.php                          (new)
app/Models/Concerns/ResolvesImageUrl.php                  (new)
app/Providers/Filament/AdminPanelProvider.php             (modified)
app/Filament/Resources/HeroSlides/ (resource + 3 pages)   (new)
app/Filament/Resources/Clients/ (resource + 3 pages)      (new)
app/Filament/Resources/Concerns/ (resource + 3 pages)     (new)
app/Filament/Resources/ContactSubmissions/ (resource + 2 pages) (new)
database/migrations/2026_10_15_000001_create_hero_slides_table.php        (new)
database/migrations/2026_10_15_000002_create_contact_submissions_table.php (new)
database/migrations/2026_10_15_000003_create_clients_table.php            (new)
database/migrations/2026_10_15_000004_create_concerns_table.php            (new)
database/seeders/HeroSlideSeeder.php                      (new)
database/seeders/ClientSeeder.php                         (new)
database/seeders/ConcernSeeder.php                         (new)
database/seeders/DatabaseSeeder.php                       (modified)
.env (APP_URL only)                                       (modified)
```
