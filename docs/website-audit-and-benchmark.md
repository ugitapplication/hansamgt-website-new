# HANSA Management Ltd. — Website Audit & Competitor Benchmark

**Project:** hansamgt-website-new (Laravel 12 + Filament v4)
**Live URL:** https://hansamgt.com
**Audit date:** 17 October 2026
**Prepared by:** ____________________
**Scope:** Public website, admin panel, SEO, performance, content, conversion, compliance, and competitive positioning against world-class facility-management (FM) operators and local Bangladeshi peers.

> This document is read-only analysis. No application code was modified to produce it.

---

## 1. Executive Summary

The website is a clean, faithful rebuild of the legacy CodeIgniter site on a modern Laravel 12 + Filament foundation. It is visually solid, technically maintainable, and has a well-implemented SEO redirect system. However, it currently functions as a **brochure site**, not as a **B2B sales and procurement tool**.

World-class FM websites are built around how a facilities/procurement buyer evaluates a vendor:

1. **Can this company legally and technically do the work?** (certifications, licences, insurance)
2. **Have they done it at my scale before?** (case studies, scale evidence, retention)
3. **How fast will they respond?** (response-time commitments, 24/7 contact)

HANSA's site answers none of these three questions with verifiable proof. It makes claims ("awarded and accredited by international bodies") but shows no certificates, no case studies with scope/outcome, no testimonials, and no service-level commitments. This is the single biggest gap between HANSA's site and the world-class benchmark.

### Scorecard (out of 10)

| Area | Score | Status |
|---|---|---|
| Design / visual parity | 7/10 | Faithful to legacy, clean |
| Technical foundation (Laravel/Filament) | 8/10 | Solid, maintainable |
| SEO basics (meta, canonical, GA4, sitemap) | 6/10 | Good but incomplete |
| **Conversion (lead generation)** | **3/10** | Largest weakness |
| **Trust / proof (certs, testimonials, case studies)** | **2/10** | Claims without evidence |
| Content depth | 4/10 | Thin service pages, no blog |
| Legal / compliance | 4/10 | Privacy Policy + consent-gated GA4 built (Terms set aside — no accounts or user content) |
| Accessibility | 6/10 | Skip link + ARIA present; gaps remain |
| Admin/CMS coverage | 4/10 | Most content is hard-coded |
| Performance / image optimization | 5/10 | Lazy-load present; no WebP/CDN |

> **Live progress:** completed items are marked as `[x]` in **Section 12 — Progress Tracker** (updated as work completes).

---

## 2. Competitive Benchmark

### 2.1 How FM buyers evaluate (research findings)

Industry research on FM/procurement websites (2025–2026) consistently shows:

- A commercial buyer spends **~30 seconds** deciding whether to keep reading.
- Buyers read a vendor site like a **contract exhibit**: they look for specific, checkable commitments.
- **Individual service pages**, a **short qualifying quote form**, a **downloadable company profile**, **published response times**, **certifications on the relevant page** (not just a footer badge row), and a **FAQ with schema markup** are the highest-leverage features.
- **Scale evidence beats logos**: "90 residential towers across two cities over 11 years, average response under 4 hours" is more persuasive than a wall of client logos.
- **Proof beats adjectives**: swap "eco-friendly" for the certification held; swap "bespoke" for how scoping works.

### 2.2 Feature comparison matrix

| Feature | Mitie (UK) | ISS (Global) | ABC Facilities (BD) | HANSA (current) |
|---|---|---|---|---|
| Certifications prominent (ISO / IFMA) | Yes — ISO 9001/14001/45001 | Yes | Yes — IFMA + ISO 9001:2015, hero-level | No — claim only, no badges |
| Narrative case studies | Yes, detailed | Yes — customer stories | Partial | No — image grid only |
| Insights / Blog / News | Yes + podcast | Yes + magazine + reports | No | No — 2 static items only |
| Newsletter signup | Yes | Yes | No | No |
| Careers / job portal | Yes (external) | Yes — job search | Partial | No — dead nav link |
| Downloadable reports / profile | Yes — ESG report PDF | Yes — annual reports PDF | No | No |
| Legal pages (privacy/terms/cookie) | Yes | Yes | Partial | Yes — Privacy Policy with a cookies section; Terms set aside (no accounts/user content) |
| Stat proof (scale) | Yes — 3,000+ customers, £5.6bn | Yes — 330k employees, 40k customers | Yes — 50-year legacy | Partial — count-up placeholders |
| Multilingual / locale switcher | Partial | Yes — 40+ countries | No | No — English only |
| Client portal / CMMS | Partial | Partial | No | No |
| Sectors / industries navigation | Yes | Yes | No | No |

### 2.3 Local (Bangladesh) competitive context

- **ABC Facilities Limited** leads on trust by displaying **IFMA certification and ISO 9001:2015** prominently and stating a **50-year construction/real-estate legacy**. This is a credibility weapon HANSA lacks on the web.
- **G4S Bangladesh** benefits from a global brand and trust.
- Other players (Enroute, Max Outsourcing, Guardian, Safeguard, BD Facilities Services) compete on service breadth and contactability.
- HANSA's real differentiators — **the only integrated FM company serving 5-star international hotels (Westin Dhaka, Sheraton Dhaka), a concern of Unique Group (since 1982), 1,000+ employees, 24/7 operation** — are underused on the site.

---

## 3. Critical Gaps (P0 — fix immediately)

| # | Issue | Location | Why it matters |
|---|---|---|---|
| 1 | `robots.txt` allows crawling `/admin` | `public/robots.txt` | Filament admin login can be indexed by search engines (security/hygiene) |
| 2 | No cookie consent, but GA4 fires unconditionally | `resources/views/partials/seo.blade.php:12` | Bangladesh Data Protection Act 2023 + EU visitor compliance risk |
| 3 | No Privacy / Terms / Cookie Policy pages | — | Legal + trust; data is collected with no stated policy |
| 4 | `.env` still in development mode | `.env` (`APP_DEBUG=true`, `APP_ENV=local`) | Debug pages can leak sensitive information in production |
| 5 | `MAIL_MAILER=log` | `.env:50` | Enquiries are saved to DB, but no email is actually delivered to admin or visitor |
| 6 | Timezone is UTC, not Dhaka | `config/app.php` | Admin "Received" timestamps are 6 hours off for a Dhaka business |
| 7 | Dead link: **Careers** | `partials/nav.blade.php:55`, `partials/nav-mobile.blade.php:39` | Menu promises a page that does not exist |
| 8 | Dead links: YouTube (footer), Twitter (contact cards) | `partials/footer.blade.php:62`, `pages/contact.blade.php:59` | Broken social links reduce professionalism |
| 9 | No security headers | `bootstrap/app.php` | Missing CSP, X-Frame-Options, HSTS, Referrer-Policy, Permissions-Policy |

---

## 4. Conversion & Trust Gaps (P1 — highest ROI)

These gaps most clearly separate HANSA from the world-class benchmark.

### 4.1 Homepage / hero

- **No primary CTA.** The hero (`pages/home/sections/_hero.blade.php`) is image + title only — no "Request a Quote", "Book a Site Audit", or "Call Now".
- **No trust indicators above the fold** (accreditations, review rating).
- **No pricing guidance** ("from" rate or a line on what drives price). Research shows publishing a starting price roughly halves lead volume but doubles lead quality.
- **No testimonials or review rating** (97% of buyers read reviews).

### 4.2 Contact form is under-qualifying

Current fields (`app/Http/Controllers/ContactController.php`): `name`, `email`, `phone`, `message`.

A world-class FM lead form also captures: **company, service needed, property type, location, approximate size, frequency**. This qualifies the lead, reduces spam, and speeds up response.

Also missing:
- **WhatsApp click-to-chat** — near-essential for reactive FM in Bangladesh (customers send photos of leaks/faults).
- **A separate emergency contact** line, distinct from the general office number.
- **A downloadable Company Profile PDF** — procurement teams still expect one for the tender pack.

### 4.3 Proof (claims vs evidence)

- **No certifications/awards page** (ISO, IFMA, trade licence, insurance, safety record).
- **No case studies** — the Projects page is a name/photo grid with no scope, duration, or outcome. Strong proof format: *"42-storey residential tower, full MEP maintenance, 380 units, 3-year contract, average response under 4 hours."*
- **No service-level commitments** (response times, cleaning frequencies, escalation windows) anywhere on the site.

---

## 5. Content & SEO Gaps (P2)

| Gap | Detail |
|---|---|
| **Blog / News / Insights** | Only 2 static training items on the homepage (`_news`). No `/news` route, no articles, no CMS. |
| **FAQ** | None. Combined with **FAQPage schema**, this is the most quoted format by AI search tools (ChatGPT, Perplexity, Google AI Overviews). |
| **Individual service pages are thin** | Each service page should carry process, benefits, industries served, SLAs, and FAQ — each is a ranking and conversion asset. |
| **Location / service-area pages** | No dedicated pages for high-intent local searches (e.g. "facility management company Dhaka/Banani/Uttara"). |
| **Structured data** | Only `Organization` JSON-LD. Missing **LocalBusiness** (Dhaka address/geo — critical for local SEO), BreadcrumbList, Service, FAQPage, Article, Review. |
| **Multilingual (Bangla)** | None; English only. A Bangla site is a strong local-market advantage. |
| **Breadcrumbs, HTML sitemap, site search** | None. |
| **Custom 404 & Thank-you page** | None — Laravel default error pages are shown. |
| **Google Maps embed** | Deliberately omitted (`_offices.blade.php`), but directions matter to buyers. |
| **Sitemap `lastmod`** | Static sitemap with no `lastmod`/`changefreq`; not regenerated on content change. |
| **Per-page `robots` / `hreflang`** | None. |
| **Footer link depth** | Legacy had 16 service links; current footer has 4 (`partials/footer.blade.php`). |

---

## 6. Admin / CMS Gaps

Only **Hero Slides, Clients, and Concerns** are editable from the admin panel. Everything else is **hard-coded in Blade**, which creates a developer dependency for routine marketing changes:

- All page copy (Company Profile, Mission/Vision, Charter, CSR, all service pages).
- Management team (`pages/management.blade.php`), Gallery (`pages/gallery.blade.php`), Projects (`pages/projects.blade.php`).
- Homepage sections: policy, numbers, glance, why-choose, news, offices, rail.
- Navigation, footer, and social links.
- **Phone numbers and email addresses are duplicated across many files** — a drift risk (e.g. all contact-card booths currently show the same mobile number).

**Recommended:** a Site Settings module (phone, social, addresses) plus Pages, Services, Projects, Gallery, Team, and News resources, and per-page SEO fields.

---

## 7. Technical Gaps

- **No application/query caching.** The homepage runs three DB queries on every request; no cache layer.
- **No image optimization** — no WebP/AVIF conversion, no `srcset`/responsive sizes. (The `@vite` build is present and correct: `public/build/manifest.json` exists.)
- **Two background-image bands** set via inline `style` (`_numbers.blade.php`, `_why.blade.php`) — not lazy-loaded or preloaded.
- **No automated tests** — only the two stock `ExampleTest` files.
- **No 2FA on admin**; `/admin` is a predictable path.
- **Contact office cards** show incorrect/duplicate phone numbers.

---

## 8. What Is Already Good (preserve this)

- Clean Laravel 12 + Filament v4 architecture.
- Complete legacy 301 redirect system (query preserved, POST-safe) — the strongest part of the migration.
- Contact pipeline stores submissions first (no enquiry lost even if mail fails).
- Per-page SEO meta, canonical, Open Graph/Twitter tags.
- Graceful DB-to-Blade fallback, so the site never breaks from missing content.
- Accessibility touches: skip link, ARIA, reduced-motion, lazy-loading.
- Lightweight vanilla JS (no jQuery/slider plugins) — performance-friendly.
- GA4, Organization JSON-LD, sitemap, robots.txt present.
- Documentation (`docs/`) is unusually thorough.

---

## 9. Prioritized Roadmap

### Phase 1 — Quick Wins (1–2 weeks, low effort / high impact)
1. Add `Disallow: /admin` to `public/robots.txt`.
2. Production `.env`: `APP_ENV=production`, `APP_DEBUG=false`, real SMTP, and set the timezone to `Asia/Dhaka`.
3. Fix dead links: Careers (build a page or remove from nav), YouTube, Twitter.
4. Extend the contact form: Company, Service needed (dropdown), Property type, Location, plus a honeypot.
5. Add **WhatsApp click-to-chat** (header + contact) and a **separate emergency number**.
6. Add a primary hero CTA: "Request a Quote" / "Talk to an Expert".
7. Add Privacy, Terms, and Cookie Policy pages + footer links + a **cookie consent banner** (GA4 consent-gated).
8. Add **LocalBusiness** structured data (geo, opening hours).
9. Publish a **downloadable Company Profile PDF**.

### Phase 2 — Trust & Proof (3–6 weeks)
10. **Certifications / Compliance page** (ISO, IFMA, trade licence, insurance, safety record).
11. **Testimonials** + Google rating widget (with client sector/name where permitted).
12. **3–5 real case studies** with scope, scale, duration, outcome, and response time.
13. Add a **service-level ledger** (response times, frequencies) + FAQ + FAQPage schema to each service page.
14. Expand the **admin CMS**: Site Settings + Pages/Services/Projects/Gallery/Team/News resources.
15. Make the copyright year dynamic (currently "©2026").

### Phase 3 — Depth & Growth (2–3 months)
16. **News / Insights blog** (training, projects, industry) as a content engine.
17. **Location / service-area pages** (Dhaka, Banani, Uttara, etc.).
18. **Bangla** language version + locale switcher.
19. **Careers page** (job listings, or a simple "Why work with us" + form).
20. Image pipeline: WebP/AVIF, `srcset`, CDN; add homepage data caching.
21. Google Maps embed (at least on the contact page).
22. Breadcrumbs, HTML sitemap, custom 404 and Thank-you pages.

---

## 10. Top Three Wins

1. **Show proof, not adjectives.** The site claims accreditation but displays no certificates. If IFMA/ISO/safety credentials are held, put the badges above the fold.
2. **Answer the procurement buyer's three questions on every service page**, as a service-level table: can you do it (cert/licence), have you done it at scale (case study/retention), how fast (response-time commitment).
3. **Capture leads properly:** WhatsApp + a short qualifying quote form + a downloadable profile + a response-time promise + FAQ schema. These few changes drive more qualified enquiries over 3–6 months than any visual polish.

---

## 11. Verification Notes

- Confirmed present: `public/build/manifest.json` (Vite build OK), `public/.htaccess` (legacy redirect rules), `public/sitemap.xml` (14 URLs, incl. `/careers`), `public/robots.txt` (disallows `/admin`).
- Confirmed gaps: no `resources/views/errors/` directory; no legal pages; no `/news` route; only `Organization` structured data.
- `.env` values are development-oriented (`APP_DEBUG=true`, `MAIL_MAILER=log`); application timezone set to `Asia/Dhaka`.
- `/careers` route and page now exist (see Section 12 — Progress Tracker).

---

## Appendix A — Public Routes

| Route | Controller method | View | Contents |
|---|---|---|---|
| `/` | `index` | `pages/home.blade.php` | 14-section homepage |
| `/company-profile` | `history` | `pages/company-profile.blade.php` | History + About sidebar |
| `/mission-vision` | `vision` | `pages/mission-vision.blade.php` | Mission/Vision cards + sidebar |
| `/charter-of-unique-group` | `charter` | `pages/charter-of-unique-group.blade.php` | Values timeline + sidebar |
| `/management` | `management` | `pages/management.blade.php` | 4 executives, flip cards + dialogs |
| `/corporate-social-responsibility` | `csr` | `pages/corporate-social-responsibility.blade.php` | CSR statement + sidebar |
| `/contact` | `contact` | `pages/contact.blade.php` | Info strip, 4 offices, form |
| `/hospitality-management` | `hardService` | `pages/hospitality-management.blade.php` | Service list + sidebar |
| `/security-fire-life-safety` | `softService` | `pages/security-fire-life-safety.blade.php` | Service list + sidebar |
| `/cleaning-white-glove-housekeeping` | `payperUse` | `pages/cleaning-white-glove-housekeeping.blade.php` | Service list + sidebar |
| `/mep-mechanical-electrical-plumbing` | `riskManagement` | `pages/mep-mechanical-electrical-plumbing.blade.php` | Service list + sidebar |
| `/projects` | `project` | `pages/projects.blade.php` | 11 projects + filter |
| `/gallery` | `siteManagement` | `pages/gallery.blade.php` | 12 photos + lightbox |
| `/careers` | `careers` | `pages/careers.blade.php` | Careers intro, benefits, recruitment areas, how to apply |
| `POST /contact` | `store` | — | Throttled form handler |

## Appendix B — Homepage Sections (in order)

Hero → Slogan band → Policy cards → Our Services → HANSA in Numbers → Services at-a-glance → Why Choose HANSA → Valued Clients → Service Projects → Business Concerns → Latest News → Let's Talk form → Offices → Quick-link rail.

## Appendix C — Dead / Placeholder Links

| File | Line | Link | Status |
|---|---|---|---|
| `partials/nav.blade.php` | 55 | Careers | ✅ Fixed — links to `/careers` |
| `partials/nav-mobile.blade.php` | 39 | Careers | ✅ Fixed — links to `/careers` |
| `partials/footer.blade.php` | 63 | YouTube (`#`) | ❌ Open |
| `pages/contact.blade.php` | 59 | Twitter (`#`) | ❌ Open |
| `partials/nav.blade.php` | 6, 29 | "About Us" / "Our Services" parent (`#`, intended dropdowns) | Intended |

---

## 12. Progress Tracker (living checklist)

> **Standing goal:** the website must be **100% SEO-friendly**. Every change from here on is judged against this; SEO tasks are tracked in the phases below.
>
> Single source of truth for status. Items are marked `[x]` when completed and updated as work finishes. Mirrors the roadmap in Section 9.

### Phase 1 — Quick Wins
- [x] 1. Add `Disallow: /admin` to `public/robots.txt`
- [x] 2. Set application timezone to `Asia/Dhaka`
- [ ] 2b. Production `.env`: `APP_ENV=production`, `APP_DEBUG=false`, real SMTP — *deferred while in development*
- [x] 3. Dead link: Careers — page built and header nav wired (desktop + mobile)
- [ ] 3b. Dead links: YouTube (footer), Twitter (contact cards)
- [ ] 4. Extend contact form (company, service needed, property type, location, honeypot)
- [ ] 5. Add WhatsApp click-to-chat + separate emergency number
- [x] 6. Add primary hero CTA ("Request a Quote" → `#emr_contact` + "Call" button)
- [x] 7. Privacy Policy page (`/privacy-policy`, carries the cookies disclosure) + contact-form privacy notice + **consent-gated GA4** (Consent Mode v2, banner, footer "Cookie settings"). Scope revised — **Terms set aside** (no accounts, transactions or user content), and one policy page instead of separate Privacy + Cookie pages so there is no duplicate content
- [ ] 7b. **Confirm the two policy values** set at the top of `resources/views/pages/privacy.blade.php` — enquiry retention (`24 months`) and GA4 data retention (`14 months`, which must match the retention period actually configured in the GA4 property, or the policy is inaccurate). Also revisit the wording if analytics is ever switched off via `GA4_ENABLED=false`
- [x] 8. Add `LocalBusiness` structured data (plus `Organization`, `WebSite`, `WebPage`, `BreadcrumbList` and per-service `Service` in the `partials/seo.blade.php` `@graph`)
- [ ] 9. Publish downloadable Company Profile PDF

### Extra completed (beyond the original roadmap)
- [x] Add `/careers` to `public/sitemap.xml`
- [x] Add Careers link to footer "Company" column
- [x] Dynamic copyright year (already `date('Y')` in `partials/footer.blade.php`)
- [x] SEO meta manager in `partials/seo.blade.php` — per-page `robots` meta, `og:type`, per-page `og:image`, `twitter:*`
- [x] Structured-data `@graph`: `Organization`+`LocalBusiness`, `WebSite`, `WebPage`, `BreadcrumbList`, and per-service `Service` on the 4 service pages
- [x] Security-headers middleware (`app/Http/Middleware/SecurityHeaders.php`) — CSP, HSTS (HTTPS only), `X-Content-Type-Options`, `Referrer-Policy`, `X-Frame-Options`, `Permissions-Policy`, `X-Powered-By` removed
- [x] `X-Robots-Tag: noindex` on `/admin*` and on every 4xx/5xx response
- [x] Environment-aware indexing — `noindex, nofollow` unless `APP_ENV=production`
- [x] `config/seo.php` — single source of truth for the organisation / local-business entity
- [x] `public/sitemap.xml` — added `<lastmod>`; add `php artisan sitemap:generate` (`app/Console/Commands/GenerateSitemap.php`) to regenerate it — *kept as a static file because the web server serves it directly; a dynamic `/sitemap.xml` route would require deleting the static file first*
- [x] Insights CMS — `Post` model + `create_posts_table` migration (title, slug, excerpt, body, cover image + alt, publish date/flag, meta title/description, OG image)
- [x] Filament `PostResource` (Content → Insights) with RichEditor, cover/OG uploads and a dedicated SEO section
- [x] Public `/insights` index (paginated, empty-state safe) + `/insights/{slug}` article page; both render without errors before the migration runs
- [x] Article SEO — `BlogPosting` + `ItemList` + `BreadcrumbList` schema, `og:type=article`, `article:published_time`/`modified_time`, per-article OG image
- [x] RSS feed at `/insights/feed` (+ `rel="alternate"` autodiscovery); `/insights` in the sitemap and the `sitemap:generate` command now appends published articles
- [x] Homepage "Latest News" section is now CMS-driven from the newest articles, falling back to the legacy static cards
- [x] `ResolvesImageUrl` accepts a field name, so cover/OG images share the same storage-vs-public resolution as hero/client images
- [x] Insights launch set — 4 articles drafted (facility-management buying checklist; fire and life safety failures; hotel housekeeping standards; MEP preventive maintenance), each with an excerpt, meta title/description, a real cover image + alt text, and internal links to the service pages; review copies with CMS field values in `docs/content/insights/`
- [x] `Database\Seeders\InsightsSeeder` — idempotent (matched on slug; publish date set only on first insert), registered in `DatabaseSeeder`; article bodies live in `database/seeders/data/insights/*.html`
- [x] Article sidebar — "More insights" (`<aside>` + `<nav aria-label>`): the 3 newest other articles with thumbnail, date and title-as-link, plus "View all insights". Excludes the article being read; hidden when there are fewer than 2 others. Two columns with a sticky aside from 1240px, single column below that so mobile/tablet are unchanged
- [x] Privacy Policy page (`/privacy-policy`) — written from the code rather than boilerplate: what the enquiry form collects, why, the lawful basis under the Personal Data Protection Act 2026, a real cookie table (`hansa-management-session`, `XSRF-TOKEN`, `_ga`/`_ga_<id>`), sharing, international transfers, retention, data-subject rights, security, children, changes and contact. "In brief" summary + "On this page" nav + breadcrumbs, `WebPage`/`BreadcrumbList` schema, canonical, and in the sitemap
- [x] Consent-gated Google Analytics 4 — `config/analytics.php`; Consent Mode v2 defaults (`analytics_storage`, `ad_storage`, `ad_user_data`, `ad_personalization` = `denied`) are declared before anything else, and the `gtag.js` library is only requested *after* acceptance. Declining therefore sends nothing to Google at all — not even a cookieless ping
- [x] Consent banner + footer "Cookie settings" — non-modal and keyboard reachable; **Accept and Decline carry equal weight** and neither is pre-selected. Revocable: declining after accepting sets consent back to `denied`, clears the `_ga*` cookies and reloads without the tag. The choice is stored in `localStorage` (`hansa.consent`, versioned) so the record of consent needs no consent itself; with no JavaScript the banner stays hidden and no analytics loads
- [x] Analytics kill-switch — `GA4_ENABLED=false` (or an empty measurement ID) removes both the tag and the banner, driven by `app/Support/Consent.php`
- [x] Privacy notice above the submit button on both live contact forms ("we use the details you send here only to respond to your enquiry…"), linked to the policy
- [x] Mobile fix — `.page-grid` now has a shrinkable `minmax(0, 1fr)` track, so the cookie table's `min-width` scrolls inside its wrapper instead of widening the column past the viewport and being clipped by the body's `overflow-x: clip`
- [x] Repo note — `resources/views/pages/partials/_contact-form.blade.php` is dead code (no view includes it; both live forms are hand-written). The privacy notice was added there too so it cannot become a gap if it is ever wired up

### Phase 2 — Trust & Proof
- [ ] 10. Certifications / Compliance page
- [ ] 11. Testimonials + rating widget
- [ ] 12. Case studies (3–5)
- [ ] 13. Service-level ledger + FAQ (+ `FAQPage` schema) per service page
- [x] 14a. Admin CMS — Insights (Posts): `Post` model + migration + Filament resource with SEO, cover and publish fields
- [ ] 14b. Admin CMS — Site Settings, Pages/Services/Projects/Gallery/Team
- [ ] 15. Dynamic copyright year — already satisfied (see above)

### Phase 3 — Depth & Growth
- [x] 16. News / Insights blog — engine built (CMS + `/insights` index/article pages + RSS + homepage hook); 4 launch articles drafted + `InsightsSeeder` ready (run `php artisan migrate` then `php artisan db:seed --class=InsightsSeeder`)
- [ ] 17. Location / service-area pages
- [ ] 18. Bangla language version + locale switcher
- [x] 19. Careers page (built; job listings / application form optional and pending)
- [ ] 20. Image pipeline (WebP/AVIF, `srcset`, CDN) + homepage caching
- [ ] 21. Google Maps embed
- [x] 22a. Breadcrumbs (visible trail + `BreadcrumbList` schema)
- [x] 22b. Custom 404 and 500 error pages (`resources/views/errors/`)
- [ ] 22c. HTML sitemap
- [ ] 22d. Thank-you page

### Change log
| Date | Item | Status |
|---|---|---|
| 2026-10-17 | robots.txt — `Disallow: /admin` | Done |
| 2026-10-17 | Application timezone → `Asia/Dhaka` | Done |
| 2026-10-17 | Careers page built + nav wired (desktop/mobile) | Done |
| 2026-10-17 | `/careers` added to sitemap + footer Company column | Done |
| 2026-10-17 | Homepage hero primary CTA ("Request a Quote" + "Call") | Done |
| 2026-10-17 | Mobile hero: original image height kept; heading/CTA scaled down to fit (arrows centred, no overlap/wrap) | Done |
| 2026-10-17 | SEO foundation: `config/seo.php` + rewritten `partials/seo.blade.php` (per-page robots meta, og:type, og:image, twitter tags, env-aware noindex) | Done |
| 2026-10-17 | Structured-data `@graph`: `Organization`+`LocalBusiness`, `WebSite`, `WebPage`, `BreadcrumbList`, per-service `Service` | Done |
| 2026-10-17 | Breadcrumbs — visible trail on every inner page + `BreadcrumbList` schema | Done |
| 2026-10-17 | Custom 404 + 500 pages (`resources/views/errors/`), noindexed | Done |
| 2026-10-17 | Security-headers middleware — CSP, HSTS, `X-Content-Type-Options`, `Referrer-Policy`, `X-Frame-Options`, `Permissions-Policy`, `X-Powered-By` removed | Done |
| 2026-10-17 | `X-Robots-Tag: noindex` on `/admin*` and all 4xx/5xx responses | Done |
| 2026-10-17 | `public/sitemap.xml` `<lastmod>` + `php artisan sitemap:generate` command | Done |
| 2026-10-17 | Insights CMS: `Post` model + `create_posts_table` migration (body, cover, publish + SEO fields) | Done |
| 2026-10-17 | Filament `PostResource` under a new "Content" navigation group | Done |
| 2026-10-17 | Public `/insights` index + `/insights/{slug}` article page, `BlogPosting`/`ItemList` schema, `og:type=article` | Done |
| 2026-10-17 | RSS feed `/insights/feed` + autodiscovery link | Done |
| 2026-10-17 | Homepage "Latest News" wired to the newest articles (static fallback kept) | Done |
| 2026-10-17 | Insights links added to desktop/mobile nav, footer, and the sitemap | Done |
| 2026-10-17 | Insights launch set — 4 service-linked articles drafted + `InsightsSeeder` (bodies in `database/seeders/data/insights/`) registered in `DatabaseSeeder` | Done |
| 2026-10-17 | Article page: "More insights" sidebar (3 newest other articles, self-link excluded, hidden under 2) — two columns from 1240px, sticky aside; mobile/tablet unchanged | Done |
| 2026-10-17 | Privacy Policy page `/privacy-policy` (carries the cookies disclosure) + route, breadcrumb, canonical, `WebPage`/`BreadcrumbList` schema and sitemap entry | Done |
| 2026-10-17 | `config/analytics.php` + `app/Support/Consent.php` — GA4 id, on/off switch and the versioned consent record in one place | Done |
| 2026-10-17 | GA4 consent-gated — Consent Mode v2 defaults `denied`, `gtag.js` requested only after acceptance (previously fired unconditionally) | Done |
| 2026-10-17 | Consent banner (`partials/consent.blade.php`) + `resources/js/consent.js` — equal-weight Accept/Decline, revocable, `localStorage`, safe with no JavaScript | Done |
| 2026-10-17 | Footer legal row — "Privacy Policy" and "Cookie settings" (the latter shown only while analytics is enabled) | Done |
| 2026-10-17 | Privacy notice above the submit button on both live contact forms | Done |
| 2026-10-17 | `.page-grid` shrinkable mobile track — the cookie table now scrolls instead of being clipped | Done |
| 2026-10-17 | Contrast pass on the new components — homepage notice 3.42:1 → 8.02:1 (translucent navy wash), policy date line 2.91:1 → 13.62:1; all 21 new text styles now meet WCAG AA | Done |
| 2026-10-17 | Long-form policy styles scoped to `.legal-doc` so they cannot leak into the shared `.page-body` (service pages and Insights articles keep their own typography) | Done |
| 2026-10-17 | Insights article lists — restored the `list-style` markers that Tailwind's preflight removed (the article bodies use plain `<ul>`), a pre-existing defect found during this work | Done |
| 2026-10-17 | Both enquiry forms now carry the **same** notice panel — the contact page matches the homepage (solid `#002e5b`, white text, 13.62:1), replacing the band-specific translucent wash so parity no longer depends on what sits behind the notice | Done |
