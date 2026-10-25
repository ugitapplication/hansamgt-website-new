# Admin Panel Audit & Configuration Plan

**Repository:** `hansamgt-website-new`
**Stack:** Laravel 12 + Filament v4
**Date:** 17 October 2026
**Scope:** What the admin panel can do today, what is still hard-coded, and what a
Website Configuration ("Site Settings") module should and should not contain.

---

## 1. Purpose

This is the audit requested before building out the admin panel. It answers three
questions:

1. **What can HANSA staff change today, without a developer?**
2. **What still requires a code change, and therefore a developer?**
3. **What belongs in a Website Configuration / Site Settings module — and what
   must deliberately stay out of it?**

The last question matters most. Some settings are safe to edit from a browser and
some are actively dangerous to store in a database. That distinction is the single
most important output of this audit, and it is covered in Section 5.

---

## 2. Method

Every Filament resource under `app/Filament/`, every model under `app/Models/`,
every migration, every Blade view under `resources/views/`, the public
controllers, `config/seo.php`, `config/analytics.php`, and `.env` were read. The
inventory in Section 4 lists the actual file that holds each hard-coded value, so
each item can be traced and, later, migrated.

---

## 3. Current Admin Panel (what exists today)

Five resources exist. The sidebar groups are declared in
`app/Providers/Filament/AdminPanelProvider.php` (`Content`, `Homepage Management`).

| Resource | Model | Sidebar group | Can add | Can edit | Can delete | Notes |
|---|---|---|---|---|---|---|
| Insights | `Post` | Content | Yes | Yes | Yes | Rich-text body, cover image, publish date, meta title/description, social image |
| Hero Slider | `HeroSlide` | Homepage Management | Yes | Yes | Yes | Image + alt + order + active flag |
| Valued Clients | `Client` | Homepage Management | Yes | Yes | Yes | Logo + alt + order + active flag |
| Business Concerns | `Concern` | Homepage Management | Yes | Yes | Yes | Logo + alt + order + active flag |
| Contact Submissions | `ContactSubmission` | *(ungrouped)* | No | View only | Yes | View, mark read/unread, delete |

The homepage is therefore driven by the database for exactly four things: the hero
slider, the client logo carousel, the business-concerns carousel, and the latest
two articles. Each has a Blade fallback, so the site cannot break if the table is
empty.

### Gaps in the panel itself (not about content)

| Gap | Impact | Where |
|---|---|---|
| No **Users** resource | Adding or removing an admin needs a developer (`php artisan admin:create` locally, or `tinker`). There is still no UI | `CreateAdmin.php` |
| No **roles/permissions** | Every `is_admin` account can delete all content and read every enquiry | Filament Shield not installed |
| No **2FA** | `/admin` is a predictable path with password-only access | `AdminPanelProvider` |
| No **profile page** | Users cannot change their own password from the panel | `AdminPanelProvider` |
| `Contact Submissions` is **ungrouped** | It floats above the two groups in the sidebar, inconsistently | `ContactSubmissionResource` |
| No **email notification** on new enquiry | Staff must open the panel to notice a lead (mail is best-effort and currently `log`) | `ContactController` |

---

## 4. What Is Still Hard-Coded

> **Snapshot, 17 October 2026 — pre-build.** Kept as the record of what needed
> to move and where it lived. Most of 4.1 and 4.2 is done now (Tiers 1 and 2);
> see the tracker in Section 10 for current status. 4.3 remains deferred by
> design.

Everything below needs a code edit today. The file that holds it is listed so the
work can be scheduled and verified.

### 4.1 Site-wide values (repeated in several files)

| Value | Held in | Problem |
|---|---|---|
| Main phone `+8801708800870` | `partials/header`, `partials/footer`, `pages/contact`, `pages/careers`, `sections/_slogan`, `config/seo.php`, `pages/home/sections/_offices` | Seven copies — guaranteed to drift |
| Email `info@hansamgt.com` | `partials/footer`, `pages/contact`, `pages/careers`, `ContactController`, `config/seo.php` | Five copies |
| Fax `88-02-8823392` | `pages/contact` only | Not part of any central record |
| Corporate address | `partials/footer`, `config/seo.php`, `pages/contact`, `sections/_offices` | Four copies, two different formats |
| Logo `images/hansa_logo_new.png` | `partials/header` (x2), `partials/footer`, `partials/seo` (favicon, apple-touch-icon, og:image, Organization logo) | Six uses, hard-coded path |
| Social links | `partials/footer` (Facebook, LinkedIn, YouTube), `pages/contact` (Facebook, Twitter, LinkedIn), `config/seo.php` (`same_as`) | **YouTube and Twitter are dead (`#`)** |
| Enquiry recipient | `ContactController::store()` | Hard-coded `info@hansamgt.com` |
| Organisation schema facts | `config/seo.php` | Name, URL, description, phone, email, address, parent, `same_as` |
| GA4 measurement ID + on/off | `config/analytics.php` (`GA4_MEASUREMENT_ID`, `GA4_ENABLED`) | Requires a code/env edit |
| Consent version | `config/analytics.php` | Requires a code edit |

### 4.2 Homepage sections

| Section | File | Hard-coded data |
|---|---|---|
| Slogan band | `sections/_slogan.blade.php` | "a concern of Unique Group" + phone |
| Commitments | `sections/_policy.blade.php` | 3 cards (Covid-19 Precautionary Measures, Safety First, HANSA Never Sleeps) — image, title, description, inline SVG icon |
| Our Services | `sections/_services.blade.php` | 4 service cards (title, paragraph, link, icon) |
| HANSA in Numbers | `sections/_numbers.blade.php` | 5 statistics: No 1, 1000+ Employees, N+ Years, 120+ Happy Customers, 24/7 |
| Services at-a-glance | `sections/_glance.blade.php` | 8 flip cards — image, title, paragraph |
| Why To Choose HANSA | `sections/_why.blade.php` | 12 bullet points across two lists |
| Service Projects | `sections/_projects.blade.php` | 4 featured projects |
| Latest News | `sections/_news.blade.php` | Database-backed (Post) — **already editable** |
| Valued Clients | `sections/_clients.blade.php` | Database-backed (Client) — **already editable** |
| Business Concerns | `sections/_concerns.blade.php` | Database-backed (Concern) — **already editable** |
| Let's Talk form | `sections/_contact.blade.php` | Form markup (no data) |
| Offices | `sections/_offices.blade.php` | 4 offices — title, address, landline |
| Quick-link rail | `sections/_rail.blade.php` | 7 homepage anchor links, rendered site-wide from the layout (structural — fine to leave in code) |

### 4.3 Inner pages

| Page | File | Hard-coded data |
|---|---|---|
| Company Profile | `pages/company-profile.blade.php` | Long-form history copy + sidebar |
| Mission & Vision | `pages/mission-vision.blade.php` | Mission/Vision cards + sidebar |
| Charter of Unique Group | `pages/charter-of-unique-group.blade.php` | Values timeline + sidebar |
| Management | `pages/management.blade.php` | 4 executives — photo, name, role, teaser, long biography, dialog id |
| CSR | `pages/corporate-social-responsibility.blade.php` | Statement copy |
| Contact | `pages/contact.blade.php` | Info strip (phone, fax, email), **4 office cards (duplicate of `_offices`)**, 3 social links, form |
| 4 service pages | `pages/hospitality-management.blade.php`, `security-fire-life-safety.blade.php`, `cleaning-white-glove-housekeeping.blade.php`, `mep-mechanical-electrical-plumbing.blade.php` | Long-form service copy, service lists, sidebar links |
| Projects | `pages/projects.blade.php` | 11 projects — image, alt, title, location, ongoing/upcoming |
| Gallery | `pages/gallery.blade.php` | 12 photos — image + caption |
| Careers | `pages/careers.blade.php` | Intro, 6 benefit cards, 5 recruitment areas, apply instructions |
| Privacy Policy | `pages/privacy.blade.php` | Policy text + two business values (enquiry retention, GA4 retention) |
| Navigation | `partials/nav.blade.php`, `partials/nav-mobile.blade.php` | Menu structure and every link |
| Footer | `partials/footer.blade.php` | Service links, company links, contact block, socials, legal row |

---

## 5. Site Settings: What Belongs In The Admin — And What Must Not

This is the direct answer to the "social link, WhatsApp number, logo, mail etc."
question.

### 5.1 Safe to store in the database (build a Settings module)

These are public values. Leaking them costs nothing, and staff should own them.

| Setting | Why it belongs here |
|---|---|
| **Logo** (header), **footer logo**, **favicon**, **default social/OG image** | Removes the hard-coded `images/hansa_logo_new.png` path from 6 places; a rebrand becomes an upload |
| **Primary phone**, **secondary/emergency phone**, **WhatsApp number**, **fax**, **email** | Kills the 7-copy duplication above; one edit updates the footer, header, contact page, careers page and schema |
| **WhatsApp** — number + on/off toggle | Feeds the planned click-to-chat (roadmap item 5) |
| **Enquiry recipient email** | Replaces the hard-coded address in `ContactController`; lets enquiries route to sales or operations without a deploy |
| **Corporate address** | Single source for footer, contact page and schema |
| **Social links** — Facebook, LinkedIn, YouTube, X/Twitter, Instagram, each with an on/off | Fixes the two dead links; a blank/disabled link can be hidden automatically instead of rendering `#` |
| **GA4 measurement ID** + **GA4 on/off** | Marketing owns analytics instead of a `.env` edit |
| **Consent version** | Must be bumped when cookies/purposes change — a config value staff should not need to find |
| **SEO defaults** — default meta description, title suffix, default OG image | Avoids editing `config/seo.php` |
| **Schema/LocalBusiness facts** — legal name, description, **latitude**, **longitude**, **opening hours**, founding year, parent company name + URL | These are the fields currently "pending from the client". A settings form lets HANSA fill them in later and immediately unlocks richer LocalBusiness search results |
| **Legal** — privacy policy "last updated" date | Avoids editing Blade for a date change |
| **Feature toggles** — show WhatsApp button, show cookie-settings link, maintenance notice | Small operational switches |

### 5.2 Must stay in `.env` / config — never in the admin panel

| Setting | Why it must not be in the database |
|---|---|
| **`MAIL_*` SMTP credentials** (host, port, username, password) | These are secrets. Stored in the DB they sit in plaintext, are readable by every `is_admin` account, and are stolen with any database leak or SQL injection. **Mail credentials belong in `.env`.** |
| **`APP_KEY`** | Encrypts sessions and cookies. Exposing or rotating it via a web form breaks every session and can expose encrypted data |
| **`APP_ENV`, `APP_DEBUG`, `APP_URL`** | `APP_DEBUG=true` in production leaks stack traces and file paths to visitors |
| **`DB_*` credentials** | Self-evident |
| **`CACHE_STORE`, `QUEUE_CONNECTION`, `SESSION_DRIVER`, Redis** | Infrastructure, not content |
| **`AWS_*` / storage keys** | Secrets |

**On the "mail" part of the request specifically:** the right admin experience is
**not** a form that writes SMTP credentials. It is:

1. The **recipient address** in Site Settings (safe — public info).
2. A **read-only status row** showing the transport and whether mail is
   configured (no secrets shown).
3. A **"Send test email"** button so staff can confirm delivery without a
   developer.

If credentials genuinely must be editable from a browser later, that is a
different decision with real security cost, and it should be raised explicitly —
it should not be smuggled in as a convenience field.

---

## 6. Recommended Target Structure

```
Content
  Insights                 (exists)
  Services                 (new — 4 records)
  Projects                 (new — 15 records)
  Gallery                  (new)
  Team                     (new — Management page)
  Testimonials             (new — needs client sign-off)
  Certifications           (new — needs client sign-off)
  FAQs                     (new — powers FAQPage schema)

Homepage Management
  Hero Slider              (exists)
  Valued Clients           (exists)
  Business Concerns        (exists)
  Homepage Sections        (new — commitments, glance, why-choose, numbers, featured projects)

Settings
  Site Settings            (new — Section 5.1)
  Offices                  (new — 4 records; feeds contact page + schema)

Operations
  Contact Submissions      (exists — move into this group, add notification + export)
  Users                    (new — with roles if permissions are wanted)
  Redirects                (optional)
```

---

## 7. Recommended Build Order

Ordered by return per unit of effort. Each tier is independently useful.

### Tier 1 — Site Settings + Offices *(highest ROI, do first)*

One settings page plus one small Offices resource. This alone removes the
duplication of phone, email, address, logo and social links across ~10 files,
fixes the two dead social links, and gives HANSA a form for the
latitude/longitude/opening-hours fields that unlock richer search results.

The reason to do this **before** the bigger content modules is that the content
modules depend on it: office addresses feed both the contact page and the
structured data, so the central record must exist first.

### Tier 2 — Content resources for data that already exists

Projects, Gallery, Team, Homepage Sections. No new copy is needed; the data is
already on the site and only has to move from Blade into tables — the same
"DB with a Blade fallback" pattern the hero slider and clients already use.

### Tier 3 — Content resources that need input from HANSA

Testimonials, Certifications, FAQs, Services. These need real content from the
client (and, for testimonials, permission to name clients). Building the screens
before the content exists would create empty admin modules.

### Tier 4 — Operations and hardening

Users + roles, email notification on enquiry, contact-submission export, 2FA,
activity log.

### Deliberately deferred

- **Pages (Company Profile, Mission & Vision, Charter, CSR)** and the **4 service
  pages** have bespoke layouts (sidebars, timelines, card grids). Turning them
  into free-form rich-text would destroy that design. The right move is to make
  the *text blocks* editable within a fixed layout — more work than a simple
  table, and best done once the simpler resources are proven.
- **Navigation** — a menu builder is a large project for a menu that changes
  rarely; a developer edit is acceptable.
- **Booking / payments** — out of scope (no accounts or transactions on this
  site).

---

## 8. Data-Integrity Defects Found During The Audit

These are bugs, not missing features, and should be fixed regardless of what the
admin panel becomes:

1. **Every office card shows the same mobile number.** `pages/contact.blade.php`
   renders `01708800870` for all four offices, including the three that have no
   mobile — the loop never reads the per-office landline into that row.
2. **Every office card shows the same email.** Same cause.
3. **The four offices are defined twice**, in `pages/contact.blade.php` and
   `pages/home/sections/_offices.blade.php`, with slightly different titles
   ("Service Booth" vs "Service booth").
4. **Two dead social links render `#`** — YouTube in the footer, Twitter on the
   contact page — which also sends a broken consent banner/mailto-style target.
5. **The contact form captures only name, email, phone and message.** Without
   company / service / property type / location the enquiry cannot be qualified or
   routed (roadmap item 4).
6. **`MAIL_FROM_ADDRESS` is still `hello@example.com`** in `.env` — correct for
   local, **wrong for launch**. Must be a real `@hansamgt.com` address before go-live.
7. **Contact Submissions sits outside the navigation groups**, so the sidebar is
   visually inconsistent.

---

## 9. Open Questions For HANSA

1. **Mail:** do you want the enquiry **recipient** editable in the panel (safe),
   with a "Send test email" button? Confirm you agree SMTP **credentials** stay in
   `.env`.
2. **Permissions:** do you need a second admin role (e.g. a marketing user who
   can edit content but not see enquiries or delete records)? This decides whether
   Roles & Permissions is scheduled.
3. **Imagery:** should the admin accept **image uploads** for logo, gallery,
   projects and team photos (requires storing files under `storage/app/public` and
   a storage link), or keep images as developer-placed files and only edit text?
4. **Content ownership:** who supplies the content for Testimonials,
   Certifications and FAQs, and has client permission been obtained where names
   would be published?
5. **LocalBusiness fields:** can you supply real latitude/longitude and opening
   hours? They stay empty (and omitted from schema) until you do.

---

## 10. Progress Tracker

- [x] Admin panel + website configuration audit completed (this document)
- [x] Tier 1 — Site Settings module (`settings` table, `App\Support\Settings`, `config/site.php`, `app/Filament/Pages/ManageSettings.php`)
- [x] Tier 1 — Offices resource (`offices` table, `Office` model, Filament resource, `OfficeSeeder`)
- [x] Tier 1 — settings wired into the public site (header, footer, contact, offices section, hero, slogan, careers, service sidebar, privacy, 404/500, sitemap, structured data)
- [x] Tier 1 — "Send test email" action + read-only mail status (credentials stay in `.env`)
- [x] Tier 1 — verified live after `migrate` + `OfficeSeeder` + `npm run build` (16/16 routes 200, no console errors, every settings-driven surface correct); stale `offices` rows repaired
- [x] Move Contact Submissions into a navigation group (`Operations`)
- [x] Fix Section 8 defects 1, 2, 3, 4 and 7
- [ ] Fix Section 8 defect 5 — qualify the enquiry form (company, service, property, location)
- [ ] Fix Section 8 defect 6 — set a real `MAIL_FROM_ADDRESS` before launch
- [x] Tier 2 — Projects resource (`projects` table, `Project` model, Filament resource, `ProjectSeeder`) — homepage band reads the featured rows
- [x] Tier 2 — Gallery resource (`gallery_images` table, `GalleryImage` model, Filament resource, `GallerySeeder`)
- [x] Tier 2 — Team resource (`team_members` table, `TeamMember` model, Filament resource, `TeamSeeder`)
- [x] Tier 2 — Homepage Sections resource (`homepage_section_items` table, `HomepageSectionItem` model, Filament resource, `HomepageSectionSeeder`)
- [x] Fix the image-field data-loss bug (Section 12) — a seeded image used to be dropped from the form and wiped on save
- [ ] Tier 2 — verify live after `migrate` + the four seeders (public pages fall back until then)
- [ ] Tier 3 — Testimonials resource
- [ ] Tier 3 — Certifications resource
- [ ] Tier 3 — FAQs resource
- [ ] Tier 3 — Services resource
- [ ] Tier 4 — Users + roles
- [ ] Tier 4 — Enquiry email notification
- [ ] Tier 4 — Contact submission export
- [ ] Tier 4 — 2FA

---

## 11. Tier 1 Build — What Shipped

### Data layer

| Piece | File |
|---|---|
| `settings` table (key/value) | `database/migrations/2026_10_17_000002_create_settings_table.php` |
| `offices` table | `database/migrations/2026_10_17_000003_create_offices_table.php` |
| `Setting`, `Office` models | `app/Models/Setting.php`, `app/Models/Office.php` |
| Settings reader, defaults and image-URL resolution | `app/Support/Settings.php` |
| The default for every key | `config/site.php` |
| The four published offices | `database/seeders/OfficeSeeder.php` |

`config/seo.php` was reduced to `default_robots`. The organisation facts that used
to live there are now settings, so the values previously duplicated across the
header, the footer, the contact page, the sitemap command and this config have one
source.

### Admin panel

- **Settings → Site Settings** (`app/Filament/Pages/ManageSettings.php`, served at
  `/admin/settings`) — five tabs: Contact, Branding, Social profiles, Analytics &
  legal, SEO & structured data.
- **Settings → Offices** — full CRUD, drag-to-reorder, active toggle.
- **Operations → Contact Submissions** — moved out of the ungrouped sidebar area.
- A **Send test email** header action, plus the mail transport and sender shown
  read-only inside the Contact tab. **No credential field exists**, by design.

### The settings keys

| Key | Default | Read by |
|---|---|---|
| `logo`, `footer_logo`, `favicon`, `og_image` | `images/hansa_logo_new.png` | header, footer, `<head>`, structured data |
| `legal_name`, `site_url`, `description` | current values | titles, structured data, sitemap |
| `footer_about` | the existing footer sentence | footer |
| `parent_name`, `parent_url` | Unique Group | `parentOrganization` |
| `founding_year`, `latitude`, `longitude`, `opening_hours` | `2015`, empty | structured data — geo and hours published only when set |
| `phone_primary`, `phone_emergency`, `whatsapp`, `fax`, `email` | current values | header, footer, contact, careers, hero, slogan, service sidebar, 404/500, privacy, confirmation email |
| `enquiry_recipient` | `info@hansamgt.com` | `ContactController` |
| `postal_address`, `street_address`, `address_locality`, `postal_code`, `address_country` | current values | footer, privacy, structured data |
| `facebook`, `linkedin`, `youtube`, `twitter`, `instagram` | two real, three empty | footer, contact page, `sameAs` |
| `ga4_enabled`, `ga4_id`, `consent_version` | current values | consent banner, GA4 tag |
| `privacy_updated` | `2026-10-17` | privacy policy |

`GA4_ENABLED=false` in `.env` remains a hard kill switch that overrides the panel.

### Defects that fall out of the central record

- The duplicated phone and email on every office card (defects 1 and 2) are gone:
  an office shows a phone or an email only when it has one of its own.
- The two copies of the office list (defect 3) are gone: one table, one controller
  method, one built-in fallback.
- The dead `#` social links (defect 4) cannot return: a profile with no URL is not
  rendered at all.
- Contact Submissions now sits under Operations (defect 7).
- The phone number is stored once; both the `tel:` href and the local display form
  are derived from it, so the two can no longer disagree.

### Commands to run

```
php artisan migrate
php artisan db:seed --class=OfficeSeeder
npm run build
```

`OfficeSeeder` is idempotent (`updateOrCreate` on the office name), so it is safe
to run again. Until it runs, the contact page and the homepage offices section fall
back to the built-in list in `HomeController`.

### Verified live — 2026-10-17

Run after `php artisan migrate` + `php artisan db:seed --class=OfficeSeeder` +
`npm run build`, against the local server.

| Check | Result |
|---|---|
| All 16 public routes | `200` |
| Console errors / warnings | `0` |
| Rebuilt stylesheet | `app-DPaQ56FP.css` — contains `.form-privacy--panel`, `.legal-doc`, `.office-card` |
| Enquiry notice, both forms | white on `#002e5b` (13.6:1), homepage and contact page identical |
| Header | logo, alt text and the Unique Group banner (`parent_url`) come from settings |
| Hero + slogan | `Request a Quote` → `#emr_contact`, `Call …` → `tel:+880…`, slogan phone — all settings-driven |
| Footer | Facebook + LinkedIn only; no `href="#"` profile link survives (the only remaining `#` links are the two nav dropdown parents) |
| Offices | corporate = mobile + landline + email; Midtown = landline; Uttara and Dhanmondi = address only |
| Sitemap | 20 URLs, 20 `<lastmod>` values, includes `/privacy-policy`, `/insights` and the four articles |
| `robots.txt` | `Disallow: /admin` + settings-driven sitemap URL |
| Insights | index = 4 articles + `ItemList`; article = `BlogPosting` + own canonical + "More insights" sidebar; feed = valid `application/rss+xml`, 4 items |
| Consent | defaults `denied`; no `gtag.js` until Accept; then `G-NJDKQTDFB5` loads and `{version:1,analytics:true}` is stored |
| Admin panel | `/admin/settings`, `/admin/offices` and `/admin/offices/create` resolve and redirect to login (no 500) — both Filament classes load and register |

Still unverified: the rendered Filament forms and tables themselves (they need a
logged-in session), and the enquiry → email path (see Section 8, defect 6).

#### Data defect found and repaired during verification

The `offices` rows in the local database did **not** match `OfficeSeeder`: all four
carried the corporate mobile `+8801708800870` and `info@hansamgt.com`, i.e. a live
copy of the very defect this build removes. Nothing in the repository writes those
values — the migration inserts no rows, and `OfficeSeeder`, `DEFAULT_OFFICES`, the
`Office` model and `OfficeResource` all record the mobile and the email for the
corporate office alone — so the rows predate or bypass the current code.

Running `OfficeSeeder` through the application set the three booths back to `null`;
the homepage and the contact page now show a per-office number or nothing. Because
the seeder is idempotent, re-running it is the repair if the values reappear.

---

## 12. Tier 2 Build — What Shipped

Four content resources, all following the same "table + model + Filament
resource + seeder + built-in Blade fallback" pattern Tier 1 established for the
office list. Nothing was invented: every record is transcribed from the Blade
partial it replaces, including the odd bits noted below.

### Data layer

| Piece | File |
|---|---|
| `projects` table | `database/migrations/2026_10_17_000004_create_projects_table.php` |
| `gallery_images` table | `database/migrations/2026_10_17_000005_create_gallery_images_table.php` |
| `team_members` table | `database/migrations/2026_10_17_000006_create_team_members_table.php` |
| `homepage_section_items` table | `database/migrations/2026_10_17_000007_create_homepage_section_items_table.php` |
| `Project`, `GalleryImage`, `TeamMember`, `HomepageSectionItem` models | `app/Models/` |
| Shared image-URL resolution | `app/Support/ImageUrl.php` (used by `ResolvesImageUrl`, `Settings` and the panel) |
| The eleven published projects | `database/seeders/ProjectSeeder.php` |
| The twelve gallery photos | `database/seeders/GallerySeeder.php` |
| The four executives | `database/seeders/TeamSeeder.php` |
| The 28 homepage items | `database/seeders/HomepageSectionSeeder.php` |

### Admin panel

| Resource | Group | URL | Rows |
|---|---|---|---|
| Projects | Content | `/admin/projects` | 11 |
| Gallery | Content | `/admin/galleries` | 12 |
| Team | Content | `/admin/team-members` | 4 |
| Homepage Sections | Homepage Management | `/admin/homepage-section-items` | 28 |

All four are drag-to-reorder (`sort`), have an Active toggle and an image
preview in the list. Projects additionally carry a **Homepage card** group
(`is_featured`, homepage title/location/alt text and homepage order).

### Two presentations of the same project

The homepage "Service Projects" band shows four of the eleven projects with
**longer marketing titles, different locations and a different order** than the
Projects page — e.g. "SHERATON DHAKA / Kemal Ataturk Avenue, Banani, Dhaka" on
the Projects page vs "5-Star International Chain Hotel Sheraton Dhaka /
Banani, Dhaka" on the homepage. Rather than overwrite either, the record now
keeps both: `title`/`location`/`image_alt` for the Projects page and the
`homepage_*` columns (with `homepage_sort`) for the band, each falling back to
the other when left empty. `ProjectSeeder` preselects the four featured rows and
their published homepage order.

### How the homepage sections were sliced

`homepage_section_items` holds the four blocks that belong to no other module,
told apart by `section`:

| Section | Items | Fields used |
|---|---|---|
| `commitments` | 3 policy cards | image, alt, icon key, heading, text |
| `numbers` | 5 statistics | value (or a founding year), label |
| `glance` | 8 service cards | image, heading, text |
| `why` | 12 bullet points | text, column (1 or 2) |

Two homepage bands were deliberately **left out**: the "Our Services" cards
belong to the Services content type (Tier 3), and "Service Projects" is the
featured flag on Projects.

Kept in code, not content: the three commitment icons (traced from the legacy
stm icon font) stay in `_policy.blade.php` and the item only stores which one to
draw; and the "HANSA in Numbers" count-up keeps working because the animation's
`target`/`prefix`/`suffix` are derived from the stored figure — an editor types
`1000+` or `No 1` and the script still counts up. "Years in operation" is stored
as `value_from_year = 2015`, so it re-counts itself every year and cannot go
stale.

### A data-loss bug found while wiring the images

Filament's `FileUpload` filters its state on load with
`$disk->exists($file)` and drops anything it cannot find on the configured disk.
Every image that shipped with this site lives in `public/images/…`, **not** on
the `public` disk, so opening any of these records would silently clear its photo
and the next save would write `NULL` over it.

Fixed with `->fetchFileInformation(false)` (state is passed through untouched)
plus a `->getUploadedFileUsing()` callback that previews the file through
`ImageUrl::resolve()` — so both a bundled `public/images/…` path and a
panel-uploaded `storage/…` path preview correctly, and neither is ever dropped.

The same defect already existed on the Insights cover image and social-share
image (`PostResource`), where it would have wiped the cover of any seeded
article the first time it was edited; both fields were fixed too.

### Verified so far — 2026-10-17 (before seeding)

| Check | Result |
|---|---|
| `/projects` | 11 cards, 5 Ongoing + 6 Upcoming, first "WESTIN DHAKA", images from their published folders |
| `/gallery` | 12 figures, first "Top Management", alt text = caption, lightbox data attributes intact |
| `/management` | 4 flip cards, the published dialog ids preserved (`chairman-message`, `md-message`, `ceo-message`, `coo-message`) |
| Homepage "Our commitments" | 3 cards, each icon drawn (hexagons / safety / bulb) |
| Homepage "HANSA in Numbers" | `No 1`, `1000+`, `11+`, `120+`, `24/7` — count-up attributes preserved (`data-target`, `data-prefix="No "`, `data-suffix="/7"`) |
| Homepage "Services at-a-glance" | 8 cards, first "Hospitality" |
| Homepage "Why To Choose HANSA" | two columns of 6 |
| Homepage "Service Projects" | the published four, in the published order, with their longer homepage titles |
| Console errors on all four pages | `0` |
| Admin routes | `/admin/projects`, `/admin/galleries`, `/admin/team-members`, `/admin/homepage-section-items` (+ `/create`) all resolve and redirect to login — the four resource classes and their pages load and register |

Still unverified: the database-driven path (needs the seeders below) and the
rendered Filament forms and tables (need a logged-in session).

One thing learned here: a Filament resource's URL is derived from its
**directory** name, not its class. `Team/TeamMemberResource.php` produced
`/admin/team/team-members`, so both new resources pin an explicit `$slug` —
the same fix Tier 1 applied to the Site Settings page.

### Commands to run

```
php artisan migrate
php artisan db:seed --class=ProjectSeeder
php artisan db:seed --class=GallerySeeder
php artisan db:seed --class=TeamSeeder
php artisan db:seed --class=HomepageSectionSeeder
```

All four seeders are idempotent (`updateOrCreate`), so re-running them restores
the published state. Until they run, each page falls back to the built-in list
inside its Blade partial, exactly as the hero slider and client logos do — so
`migrate` alone never leaves a page blank.

Plain `php artisan db:seed` is now safe on an existing database: since 2026-10-24
`DatabaseSeeder` no longer creates a user account, and every content seeder is
idempotent (`updateOrCreate` / `firstOrNew`). Administrator accounts are created
separately with `php artisan admin:create` (local only).

---

## 13. Change Log

| Date | Item | Status |
|---|---|---|
| 2026-10-17 | Admin panel + configuration audit written (`docs/admin-panel-audit.md`) | Done |
| 2026-10-17 | Tier 1 built: settings + offices tables, `App\Support\Settings`, `config/site.php`, Site Settings page, Offices resource, `OfficeSeeder`; wired into header/footer/contact/home/hero/slogan/careers/service sidebar/privacy/404/500/sitemap/schema; enquiry recipient now a setting; "Send test email" action | Done |
| 2026-10-17 | Tier 1 verified live (Section 11) — 16/16 routes 200, 0 console errors, rebuilt bundle, consent gate and sitemap re-checked | Done |
| 2026-10-17 | Repaired stale `offices` rows that repeated the corporate mobile + email on every card; Site Settings slug pinned to `/admin/settings` | Done |
| 2026-10-17 | Tier 2 built: `projects`, `gallery_images`, `team_members` and `homepage_section_items` tables + models + Filament resources (Content / Homepage Management) + four idempotent seeders; `/projects`, `/gallery`, `/management` and the five homepage bands now read from the database with their published content as the Blade fallback | Done |
| 2026-10-17 | Fixed the `FileUpload` data-loss bug: a bundled `public/images/…` value was dropped from the form and wiped on save. Added `App\Support\ImageUrl`, `->fetchFileInformation(false)` + `->getUploadedFileUsing()` on Projects, Gallery, Team, Homepage Sections **and** the two pre-existing Insights image fields | Done |
| 2026-10-17 | Pinned `$slug` on the Team and Homepage Sections resources (their directory names produced nested `/admin/team/team-members` URLs) | Done |
| 2026-10-17 | Hero "Call" ghost-button fix mirrored into the homepage's inline critical CSS (`home.blade.php`), which still carried the old transparent background | Done |
| 2026-10-17 | Tier 2 verified pre-seed (Section 12): fallbacks render the published content byte-for-byte on 4 pages, 0 console errors, 8 admin routes register | Done |
| 2026-10-24 | Admin authorization: `users.is_admin` migration, `User implements FilamentUser` + `canAccessPanel()`, `is_admin` excluded from `$fillable`; `DatabaseSeeder` no longer creates `test@example.com`; new local-only `php artisan admin:create`; feature tests added (not yet executed) | Done (source) |
