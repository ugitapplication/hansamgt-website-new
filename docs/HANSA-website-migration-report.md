# HANSA Management Website — Migration and Development Report

**Project:** HANSA Management Ltd. corporate website
**Live URL:** https://hansamgt.com
**Date of report:** 15 October 2026
**Prepared by:** ____________________
**Approved by:** ____________________

---

## 1. Background

The previous HANSA Management website ran on CodeIgniter with a WordPress-style
theme layer (Visual Composer markup, Revolution Slider, Owl Carousel, Contact
Form 7 conventions) and controller-based URLs such as `home/history`,
`home/hardService` and `index.php`-prefixed variants.

It was migrated to a Laravel 12 application with the following objectives:

1. Clean, descriptive URLs for all public pages.
2. A lighter, maintainable frontend without page-builder or slider-plugin weight.
3. Identical public design — no visual redesign.
4. A Filament admin panel for managing homepage content and enquiries.
5. Preserved search traffic: 301 redirects from every legacy address.

**Current stack:** Laravel 12, PHP ^8.2, MySQL, Tailwind CSS v4 (Vite),
Filament v4.15.2 (admin panel at `/admin`).

---

## 2. URL Map (Old → New)

All legacy addresses return 301 to the canonical page, with query strings
preserved. POST requests are never redirected.

| Legacy URL | Current URL |
|---|---|
| `/`, `/home`, `/home/index`, `/index.php` | `/` |
| `home/history` | `/company-profile` |
| `home/vision` | `/mission-vision` |
| `home/charter` | `/charter-of-unique-group` |
| `home/management` | `/management` |
| `home/csr` | `/corporate-social-responsibility` |
| `home/contact` | `/contact` |
| `home/hardService` | `/hospitality-management` |
| `home/softService` | `/security-fire-life-safety` |
| `home/payperUse` | `/cleaning-white-glove-housekeeping` |
| `home/riskManagement` | `/mep-mechanical-electrical-plumbing` |
| `home/project` | `/projects` |
| `home/siteManagement` | `/gallery` |

Implementation: `routes/web.php` (legacy map), `public/.htaccess`
(Apache `index.php/*` rules), `app/Http/Middleware/RedirectIndexPhp.php`
(server-independent handling for `serve`/Nginx, single-hop redirects).

---

## 3. Homepage Rebuild

Route `/` (`HomeController@index`). Same section order and copy as the legacy
site; heavy plugins replaced with lightweight equivalents:

| Section | Legacy | Current |
|---|---|---|
| Hero slider (7 slides) | Revolution Slider | Custom rotator with arrows + progress bar (`_hero`) |
| Slogan band (Unique Group + phone) | particlesJS CTA | Canvas particle band (`_slogan`) |
| Policy cards (Covid / Safety / Never Sleeps) | Info boxes | Hover cards (`_policy`) |
| Our Services (4) | Icon boxes | Same 4 links (`_services`) |
| HANSA in Numbers | CountUp on fixed BG | Server-rendered figures + count-up (`_numbers`) |
| Services at-a-glance (8) | Hoverboxes | Flip cards (`_glance`) |
| Why Choose HANSA (12 points) | Gold bullet lists | Same content (`_why`) |
| Valued Clients (18 logos) | Owl Carousel | Scroll-snap carousel, autoplay 3000 (`_clients`) |
| Service Projects (4) | News list | Cards linking to `/projects` (`_projects`) |
| Business Concerns (13 logos) | Owl Carousel | Scroll-snap carousel, autoplay 950 (`_concerns`) |
| Latest News (2 training items) | Slick carousel | Static grid linking to service pages (`_news`) |
| Let's Talk form | AJAX to `sendData` | POST to `/contact` with validation (`_contact`) |
| Office blocks (4) | Address carousel + Google Map | Static cards (map omitted by design) (`_offices`) |
| Quick-link rail (7 anchors) | Floating menu | Homepage-only smooth-scroll rail (`_rail`) |

Shared chrome: sticky desktop/mobile header with Unique Group banner,
skip link, black 4-column footer (brand, services, company, contact +
socials), slogan strip on every inner page.

---

## 4. Inner Pages

All pages extend `layouts.app`, carry per-page SEO (title, description,
self-referencing canonical, Open Graph/Twitter tags) and end with the
slogan strip.

- **Company Profile** — 5-paragraph history + About sidebar.
- **Mission & Vision** — banner + two cards + About sidebar.
- **Charter of Unique Group** — intro + 6-value timeline (Integrity,
  Innovation, Quality, Synergy, Excellence, Leadership) + About sidebar.
- **Management** — 4 members (Chairperson, MD, CEO, COO) with flip cards
  and native `<dialog>` bios (replaces Bootstrap modals) + photos in
  `public/images/pages/team/`.
- **CSR** — banner + commitment statement + About sidebar.
- **Contact** — info strip (desk/fax/email), 4 office cards with brand-colour
  social hovers, working form (Section 5). No sidebar.
- **4 service pages** — banner + thumbnail + itemised lists + services
  sidebar with "How can we help?" CTA.
- **Projects** — 11 sites (5 ongoing / 6 upcoming, matching legacy labels)
  with working All/Ongoing/Upcoming filter (the legacy filter was
  non-functional; only "Projects" existed). No sidebar.
- **Gallery** — 12 captioned photos with native lightbox (replaces
  prettyPhoto). Caption typos from the old site corrected.

---

## 5. Contact System

Both public forms (homepage "Let's Talk" and contact page) POST to
`/contact` (`ContactController@store`, throttled):

1. Server-side validation with per-field messages; input preserved on error.
2. Every enquiry stored in `contact_submissions` (name, email, phone,
   message, `read_at` triage flag) — nothing is lost on mail failure.
3. Admin notification to `info@hansamgt.com` (visitor as reply-to) plus a
   confirmation email to the visitor (legacy wording kept).
4. Success banner on redirect, matching each page's design.

Mail driver is `log` in development; production requires SMTP credentials.

---

## 6. SEO, Analytics, Performance

- Per-page titles/descriptions/canonicals defined once in `HomeController`,
  rendered via `partials/seo.blade.php`.
- `public/sitemap.xml` (13 URLs) + `public/robots.txt` with sitemap line.
- GA4 (`G-NJDKQTDFB5`) on every page; Organization JSON-LD (address, phone,
  email, parent Unique Group, social profiles).
- Click-to-call on mobile numbers; landmarks/alt text retained.
- Self-hosted Avenir + legacy icon fonts; lazy-loaded imagery; hero
  preload; `overflow-x: clip` guard against full-bleed scrollbars.

---

## 7. Admin Panel (Filament, `/admin`)

Existing login retained. Sidebar:

```
Dashboard
Homepage Management
├─ Hero Slider
├─ Valued Clients
└─ Business Concerns
Contact Submissions
```

| Module | Table | Fields | Features |
|---|---|---|---|
| Hero Slider | `hero_slides` | label, image, alt, sort_order, is_active | CRUD, drag reorder, inline toggle; JPG/PNG/WebP ≤ 2 MB on `public` disk |
| Valued Clients | `clients` | name, logo, alt, sort_order, is_active | Same pattern; 18 logos seeded |
| Business Concerns | `concerns` | name, logo, alt, sort_order, is_active | Same pattern; 13 logos seeded |
| Contact Submissions | `contact_submissions` | — | Read-only list (newest first, search), detail view, mark read/unread, delete; no create |

Seeders mirror the live site exactly (no invented content), are idempotent
and never overwrite admin active/inactive choices. Homepage renders managed
rows when present and falls back to the built-in sets otherwise, so content
tables can never break the public site. Remaining planned sections (About,
Featured Services, Statistics, Featured Projects, Latest News, CTA,
Services, Projects, News, Gallery, Settings) intentionally have no sidebar
entries yet.

> **Snapshot, 15 October 2026.** Insights, Site Settings, Offices and the four
> Tier 2 content resources (Projects, Gallery, Team, Homepage Sections) were
> added afterwards, Contact Submissions moved under a new *Operations* group, and
> the sidebar now reads Content / Homepage Management / Settings / Operations —
> see `docs/admin-panel-audit.md` for the current panel and its build order.

---

## 8. Environment and Deployment Notes

- Requirements: PHP ^8.2, MySQL, Composer, Node (build only).
- After deploy: `composer install`, `php artisan migrate`, `php artisan storage:link`
  (mandatory — uploads 404 without it), seeders (`HeroSlideSeeder`,
  `ClientSeeder`, `ConcernSeeder`), `npm run build`.
- Production `.env`: `APP_URL=https://hansamgt.com`, real `MAIL_*` SMTP values.
  Local `serve` uses `APP_URL=http://127.0.0.1:8000` (must match the access URL
  or generated links/previews break); restart `serve` after `.env` changes and
  bypass browser cache when verifying 301s (`curl -I`, incognito).

### 8.1 Static asset caching (Apache)

`public/.htaccess` sets path-scoped `Cache-Control` headers for static assets.
It requires `mod_headers` + `mod_setenvif`; each block is `<IfModule>`-guarded,
so a host without them skips the rules rather than failing.

| Path | Policy | Why |
| --- | --- | --- |
| `/build/**` | `public, max-age=31536000, immutable` | Vite output; file names contain a content hash, so a rebuild changes the URL. |
| `/css/**`, `/js/**` | `public, max-age=2592000` (30 days) | Filament assets; URLs carry `?v=<package version>`, so an upgrade changes the URL. |
| `/images/**`, `/fonts/**` | `public, max-age=604800` (7 days) | Tracked files with plain names, overwritten in place — bounded rather than immutable. |

Nothing here applies to HTML/PHP responses, the contact form, the admin panel or
error responses. `Header set` (not `Header always set`) means 4xx/5xx are never
given these headers, and every rule is scoped with `env=` so no rule can apply to
every URL.

> **Unverified in production.** This repository tracks no web-server config other
> than `public/.htaccess`, so whether these rules are active depends on the host.
> Check on the real server, e.g.
> `curl -I https://hansamgt.com/build/assets/app-<hash>.css` — expect
> `cache-control: public, max-age=31536000, immutable`. If the host runs Nginx or
> ignores `.htaccess`, the equivalent `location` blocks must be configured there
> instead (outside this repository).

### 8.2 Production optimization checklist

1. **Build assets:** `npm ci && npm run build` (Vite writes `public/build/`).
2. **Verify the target environment first:** `php -v` (≥ 8.2), the `.env` values
   for *that* environment, required PHP extensions (`php -m`), and a clean
   `php artisan about`.
3. **Optimize during deploy:** run `php artisan optimize` — it caches config,
   routes/events and views in one step. Verify afterwards with `php artisan
   about` and by loading a few pages.
4. **Test the caches in the intended environment** before relying on them:
   `config:cache` bakes `.env` values into `bootstrap/cache/config.php` (later
   `.env` edits appear to do nothing until re-cached), `route:cache` fails on any
   route that cannot be serialised, and `view:cache` pre-compiles Blade.
5. **Rebuild caches after any deploy that changes routes, config or views:** run
   `php artisan optimize` again. If a rebuild is genuinely needed, `php artisan
   optimize:clear` (then `optimize`) only clears framework caches — it must never
   be confused with clearing application data, sessions or the queue.
6. These commands are **not** assumed to be enabled or scheduled in production
   today, and nothing in this repository runs them automatically.

---

## 9. Outstanding Items

1. Remaining admin modules (Section 7, last paragraph).
2. Production SMTP credentials.
3. Scroll-to-top button (existed on legacy site).
4. Footer service-link depth (legacy had 16 links, current has 4).
5. Dead social placeholders (`#`): Twitter on contact cards, YouTube in footer.
6. Homepage map: omitted by design — confirm whether to restore.
7. Full browser + migration + seed verification pass (Section 8).
8. Remove test enquiry/submission rows before handover.

---

## 10. Change Log (15 October 2026)

Frontend fixes (contact scrollbar, card social hovers); crawler files;
GA4 + JSON-LD; legacy 301 system (routes, `.htaccess`, middleware) incl.
`/index.php` loop and Blade `@context` ParseError fixes; Hero Slider,
contact backend + Submissions, Clients and Concerns admin modules with
migrations, models, seeders and homepage wiring; upload fault diagnosis
(`APP_URL`, storage link). See `docs/2026-10-15-work-report.md` for the
day-level detail.
