# HANSA Management — Technical Documentation

> **Purpose:** The authoritative, source-grounded documentation set for the `hansamgt-website-new` Laravel application.
> **Audience:** Developers, administrators, DevOps, and reviewers.
> **Last reviewed:** 2026-10-19
> **Scope:** The current implementation in this repository, as inspected read-only. Not a roadmap, not a wish list.

---

## Start here

| I want to… | Go to |
|---|---|
| Understand the project in 10 minutes | [01 — Project Overview](01-project-overview.md) |
| Set up a local copy | [04 — Local Development Setup](04-local-development-setup.md) |
| Know what is broken or risky | [21 — Known Issues, Decisions & Roadmap](21-known-issues-decisions-and-roadmap.md) |
| Deploy | [20 — Deployment & Operations](20-deployment-and-operations.md) |
| Edit content in the admin | [11 — Admin Panel Reference](11-admin-panel-reference.md) |
| Understand the code | [02 — Architecture & Request Lifecycle](02-architecture-and-request-lifecycle.md) |

---

## Verification legend

Every chapter uses the same tags. They are not decorative — they tell you how much to trust a statement.

| Tag | Meaning |
|---|---|
| ✅ | **Source-verified** — the implementing file was read directly. |
| 🟡 | **Runtime-unverified** — code was read, but the behaviour was not executed or observed. |
| ⚠️ | **Partial** — true with stated caveats, or implemented with gaps. |
| ⛔ | **Not implemented / absent** — searched for and not found. |
| 🕓 | **Legacy** — inherited from the CodeIgniter site, retained deliberately. |
| ❓ | **Unknown** — cannot be determined from the repository (live config, host, DB). |

### What "source-verified" does and does not mean

It means: *"this file, at this line, says this."* It does **not** mean the live site behaves that way. No HTTP request was made, no page was rendered, no command was executed.

### Global limitation

**The shell was unavailable throughout this audit.** Every command — including `php -v` — exited with code 66 and produced no output. Therefore:

- `php artisan route:list`, `test`, `about`, `migrate:status` were **not run**.
- `composer audit`, `npm audit`, `npm run build` were **not run**.
- Git commands were **not run**.
- The SQLite database was **not queried**.
- No browser or crawler was used.

Everything here is derived from reading source files. Where a claim depends on runtime behaviour, it is tagged 🟡 or ❓.

---

## Confidentiality rules applied

This documentation was produced under strict rules, and the rules still apply to anyone extending it:

- **No secret values.** No `.env` contents, API keys, passwords, tokens, or credentials appear anywhere. Only variable **names** and safe example values.
- **No real enquiry data.** The database and logs were not opened.
- **No reproduction of the compromised legacy Maps key.** It is referenced by location and status only. It must be revoked.
- **No application code was modified.** All changes are confined to `docs/technical/`.

---

## Chapters

### Foundations

| # | Chapter | What it covers |
|---|---|---|
| 01 | [Project Overview](01-project-overview.md) | What the site is, who uses it, the two content systems, maturity assessment, the five things to know first |
| 02 | [Architecture & Request Lifecycle](02-architecture-and-request-lifecycle.md) | Entry point, bootstrap, middleware, routing, controllers, the fallback pattern, view composition, strengths and risks |
| 03 | [Technology Stack](03-technology-stack.md) | Exact dependencies, what is used vs merely installed, build pipeline, what is deliberately absent |

### Getting running

| # | Chapter | What it covers |
|---|---|---|
| 04 | [Local Development Setup](04-local-development-setup.md) | Prerequisites, quick start, the gaps in `composer setup`, seeding, admin access, local traps |
| 05 | [Configuration Reference](05-configuration-reference.md) | Both config layers, every env variable name, all `config/site.php` defaults, interaction diagram |

### Behaviour

| # | Chapter | What it covers |
|---|---|---|
| 06 | [Routing & URL Map](06-routing-and-url-map.md) | All 19 public routes, admin routes, legacy 301s at three layers, declared vs runtime-confirmed, how to add a page |
| 07 | [Public Pages Reference](07-public-pages-reference.md) | All 18 public surfaces: URL, controller, view, SEO, data source, fallbacks, gaps |
| 08 | [Homepage Composition](08-homepage-composition.md) | All 15 blocks in order, data loading, per-block fallbacks, what is and is not editable |
| 09 | [Insights Subsystem](09-insights-subsystem.md) | Publish rules, pagination, related posts, RSS, ItemList/BlogPosting schema, admin editing, trust boundary |
| 10 | [Contact & Enquiries](10-contact-and-enquiries.md) | Form, validation, rate limit, persistence, dual mail, admin inbox, failure modes, retention gap |

### Administration

| # | Chapter | What it covers |
|---|---|---|
| 11 | [Admin Panel Reference](11-admin-panel-reference.md) | Access, the authorization reality, all 10 resources, dashboard, shared upload pattern, what the panel cannot do |
| 12 | [Site Settings](12-site-settings.md) | `settings` table, resolution logic, clear-vs-default semantics, caching, 5 admin tabs, test-email action |

### Data and media

| # | Chapter | What it covers |
|---|---|---|
| 13 | [Database & Data Dictionary](13-database-and-data-dictionary.md) | All 19 tables, columns, types, indexes, migration sources, relationships that do **not** exist, seeders |
| 14 | [Media & Image Pipeline](14-media-and-image-pipeline.md) | Two image generations, `ImageUrl` resolution, upload pattern, **the missing storage symlink**, caching |

### Discoverability, privacy, security

| # | Chapter | What it covers |
|---|---|---|
| 15 | [SEO & Structured Data](15-seo-and-structured-data.md) | Titles, canonicals, robots, OG/Twitter, the JSON-LD `@graph`, sitemap, RSS, redirects, verification checklist |
| 16 | [Analytics, Consent & Privacy](16-analytics-consent-and-privacy.md) | Consent gate chain, banner, `consent.js`, revocation, **the retention gap**, policy-vs-code accuracy audit |
| 17 | [Security & Hardening](17-security-and-hardening.md) | Headers, CSP, **authorization**, auth details, XSS trust boundaries, input handling, abuse controls, secrets, hardening checklist |

### Front end, quality, operations

| # | Chapter | What it covers |
|---|---|---|
| 18 | [Frontend Assets & Accessibility](18-frontend-assets-and-accessibility.md) | Build, Tailwind v4, CSS architecture, JS modules, interactions, a11y strengths and weaknesses, performance |
| 19 | [Testing & QA](19-testing-and-qa.md) | All 9 files / 59 methods, coverage map, highest-value missing tests, manual QA checklist |
| 20 | [Deployment & Operations](20-deployment-and-operations.md) | Release procedure, env checklist, Apache/Nginx, **launch blockers**, backups, monitoring, rollback, scheduling |
| 21 | [Known Issues, Decisions & Roadmap](21-known-issues-decisions-and-roadmap.md) | 4 launch blockers, 10 medium, 21 low, 12 runtime-unverified, 15 deliberate decisions, stale docs, roadmap |

---

## Reading paths

### New developer, day one

[01](01-project-overview.md) → [02](02-architecture-and-request-lifecycle.md) → [03](03-technology-stack.md) → [04](04-local-development-setup.md) → [06](06-routing-and-url-map.md) → [07](07-public-pages-reference.md) → [13](13-database-and-data-dictionary.md)

### Content editor

[07](07-public-pages-reference.md) → [11](11-admin-panel-reference.md) → [12](12-site-settings.md) → [08](08-homepage-composition.md) → [14](14-media-and-image-pipeline.md)

### Security review

[17](17-security-and-hardening.md) → [11 §2](11-admin-panel-reference.md#2-authorization-reality) → [16](16-analytics-consent-and-privacy.md) → [21 §1](21-known-issues-decisions-and-roadmap.md#1-launch-blockers)

### Deployment / on-call

[20](20-deployment-and-operations.md) → [05 §12](05-configuration-reference.md#12-environment-variable-reference-names-only) → [14 §4](14-media-and-image-pipeline.md#42-the-missing-symlink) → [19 §7](19-testing-and-qa.md#7-manual-qa-checklist) → [21](21-known-issues-decisions-and-roadmap.md)

### SEO / marketing

[15](15-seo-and-structured-data.md) → [09](09-insights-subsystem.md) → [07](07-public-pages-reference.md) → [16](16-analytics-consent-and-privacy.md)

---

## Coverage at a glance

| Area | Count | Documented in |
|---|---|---|
| Public routes (GET) | 18 | [06](06-routing-and-url-map.md), [07](07-public-pages-reference.md) |
| Public POST endpoints | 1 | [10](10-contact-and-enquiries.md) |
| Legacy redirect routes | 18 generated (13 map entries + 5 lowercase) + 1 `/home` + middleware + `.htaccess` | [06 §4](06-routing-and-url-map.md#4-legacy-redirect-routes) |
| Filament resources | 10 | [11](11-admin-panel-reference.md) |
| Filament resource pages | 29 (9 × list/create/edit + 2 view-only) | [11 §5](11-admin-panel-reference.md#5-resource-summary) |
| Custom admin pages | 1 | [12](12-site-settings.md) |
| Dashboard widgets | 1 | [11 §4](11-admin-panel-reference.md#4-dashboard) |
| Database tables | 19 | [13](13-database-and-data-dictionary.md) |
| Migrations | 15 | [13 §4](13-database-and-data-dictionary.md#4-migration-order) |
| Seeders | 10 | [13 §6](13-database-and-data-dictionary.md#6-seeders) |
| Eloquent models | 12 | [13](13-database-and-data-dictionary.md), [02 §5](02-architecture-and-request-lifecycle.md#5-data-access) |
| Support classes | 5 | [12](12-site-settings.md), [14](14-media-and-image-pipeline.md), [16](16-analytics-consent-and-privacy.md) |
| Test files / methods | 9 / 59 | [19](19-testing-and-qa.md) |
| Config files | 13 | [05](05-configuration-reference.md) |
| Custom commands | 1 | [15 §6](15-seo-and-structured-data.md#6-sitemap) |
| Known issues | 4 high, 10 medium, 21 low | [21](21-known-issues-decisions-and-roadmap.md) |

---

## Feature → documentation traceability

Every discovered feature maps to a chapter. This is the verification backbone of the set.

| Feature | Primary | Supporting |
|---|---|---|
| Homepage | [08](08-homepage-composition.md) | [07 §3](07-public-pages-reference.md#3-homepage--), [06](06-routing-and-url-map.md) |
| About pages (4) | [07 §4](07-public-pages-reference.md#4-about-pages) | [15](15-seo-and-structured-data.md) |
| Service pages (4) | [07 §5](07-public-pages-reference.md#5-service-pages) | [15 §5.4](15-seo-and-structured-data.md#54-service) |
| Projects | [07 §7](07-public-pages-reference.md#7-projects) | [11 §6.2](11-admin-panel-reference.md#62-projects--adminprojects), [13 §3.4](13-database-and-data-dictionary.md#34-projects) |
| Gallery | [07 §8](07-public-pages-reference.md#8-gallery) | [11 §6.3](11-admin-panel-reference.md#63-gallery--admin-galleries), [13 §3.5](13-database-and-data-dictionary.md#35-gallery_images) |
| Management / team | [07 §4.5](07-public-pages-reference.md#45-management) | [11 §6.4](11-admin-panel-reference.md#64-team--admin-team-members), [13 §3.6](13-database-and-data-dictionary.md#36-team_members) |
| Insights | [09](09-insights-subsystem.md) | [07 §11](07-public-pages-reference.md#11-insights), [13 §3.1](13-database-and-data-dictionary.md#31-posts) |
| RSS feed | [09 §5](09-insights-subsystem.md#5-rss-feed--insightsfeed) | [15 §7](15-seo-and-structured-data.md#7-rss) |
| Contact form | [10](10-contact-and-enquiries.md) | [07 §6](07-public-pages-reference.md#6-contact), [13 §3.9](13-database-and-data-dictionary.md#39-contact_submissions) |
| Enquiry inbox | [10 §8](10-contact-and-enquiries.md#8-admin-inbox--admincontact-submissions) | [11 §8](11-admin-panel-reference.md#8-contact-submissions--admincontact-submissions) |
| Site settings | [12](12-site-settings.md) | [05](05-configuration-reference.md), [13 §3.2](13-database-and-data-dictionary.md#32-settings) |
| Hero slider | [08 §3.1](08-homepage-composition.md#31-hero-_herobladephp) | [11 §6.5](11-admin-panel-reference.md#65-hero-slider--admin-hero-slides), [13 §3.8](13-database-and-data-dictionary.md#38-hero_slides) |
| Clients / concerns | [08 §3.8](08-homepage-composition.md#38-clients-_clientsbladephp) | [11 §6.6](11-admin-panel-reference.md#66-valued-clients--admin-clients), [11 §6.7](11-admin-panel-reference.md#67-business-concerns--admin-concerns) |
| Homepage sections | [08 §3.3](08-homepage-composition.md#33-policy--commitments-_policybladephp) | [11 §6.8](11-admin-panel-reference.md#68-homepage-sections--admin-homepage-section-items), [13 §3.7](13-database-and-data-dictionary.md#37-homepage_section_items) |
| Offices | [07 §6](07-public-pages-reference.md#6-contact) | [11 §7](11-admin-panel-reference.md#7-offices--adminoffices), [13 §3.3](13-database-and-data-dictionary.md#33-offices) |
| Office map | [08 §3.13](08-homepage-composition.md#313-office-map-partialsoffice-mapbladephp) | [14](14-media-and-image-pipeline.md), [17 §3](17-security-and-hardening.md#3-content-security-policy) |
| Image uploads | [14](14-media-and-image-pipeline.md) | [11 §10](11-admin-panel-reference.md#10-shared-upload-pattern) |
| SEO / JSON-LD | [15](15-seo-and-structured-data.md) | [07](07-public-pages-reference.md) |
| Sitemap | [15 §6](15-seo-and-structured-data.md#6-sitemap) | [20 §12](20-deployment-and-operations.md#12-scheduled-tasks) |
| Analytics / consent | [16](16-analytics-consent-and-privacy.md) | [12 §4.4](12-site-settings.md#44-tab-analytics--legal-193-218) |
| Privacy policy | [16 §7](16-analytics-consent-and-privacy.md#7-privacy-policy--accuracy-audit) | [07 §10](07-public-pages-reference.md#10-privacy-policy) |
| WhatsApp float | [07 §2](07-public-pages-reference.md#2-layout-and-shared-partials) | [12 §8](12-site-settings.md#8-helper-methods), [19 §2.7](19-testing-and-qa.md#27-testsfeaturewhatsappfloattestphp--6-methods) |
| Security headers | [17 §2](17-security-and-hardening.md#2-transport-and-headers) | [02 §2.3](02-architecture-and-request-lifecycle.md#23-middleware-order) |
| Legacy redirects | [06 §4](06-routing-and-url-map.md#4-legacy-redirect-routes) | [15 §8](15-seo-and-structured-data.md#8-redirects-and-canonicalisation) |
| Careers | [07 §9](07-public-pages-reference.md#9-careers) | — |
| Admin dashboard | [11 §4](11-admin-panel-reference.md#4-dashboard) | — |
| Authentication | [11 §1](11-admin-panel-reference.md#1-access) | [17 §5](17-security-and-hardening.md#5-authentication-details) |
| Authorization | [17 §4](17-security-and-hardening.md#4-authorization) | [11 §2](11-admin-panel-reference.md#2-authorization-reality) |
| Database schema | [13](13-database-and-data-dictionary.md) | [02 §5](02-architecture-and-request-lifecycle.md#5-data-access) |
| Build pipeline | [03 §4](03-technology-stack.md#4-build-pipeline) | [18 §1](18-frontend-assets-and-accessibility.md#1-build-pipeline) |
| Tests | [19](19-testing-and-qa.md) | — |
| Deployment | [20](20-deployment-and-operations.md) | [05](05-configuration-reference.md) |

---

## Relationship to the existing `docs/` files

The repository already contains five documents:

| File | Nature | Status |
|---|---|---|
| `docs/website-audit-and-benchmark.md` | audit + benchmark, Oct 2026 | partly outdated |
| `docs/2026-10-15-work-report.md` | work log | historical |
| `docs/admin-panel-audit.md` | admin audit + build tracker | mostly current; one stale claim |
| `docs/HANSA-website-migration-report.md` | migration report | partly outdated |
| `docs/maps-audit-and-plan.md` | map decision record | current |

**These were not modified or deleted.** They record history and business decisions. Where they conflict with source, this set wins — and the conflicts are listed explicitly in [21 §6](21-known-issues-decisions-and-roadmap.md#6-stale-existing-documentation).

Notable contradictions:

- A claimed "14 URLs" in `sitemap.xml` — the file has **20**.
- A claimed absent `resources/views/errors/` — both views **exist**.
- A claimed "Contact Submissions outside navigation groups" — it is in **Operations**.
- A claim that "seeders are idempotent" — the nine content seeders are; the `DatabaseSeeder` user block is **not**.
- "Filament v4.15.2" — **unverified**.
- "MySQL" as a requirement — the default is **SQLite**.

---

## Conventions used in this set

- **Relative links** between chapters, with anchors where useful.
- **Tables** for enumerations; **code blocks** for exact values, paths, and commands.
- **File paths are repo-relative** (e.g. `app/Http/Controllers/HomeController.php`).
- **Line references** are given only where the line was actually read. They are not invented.
- **No filler.** Chapters are as long as their subject requires.
- Each chapter ends with **Known limitations** and **Related chapters**.

---

## Maintenance

| Rule | Detail |
|---|---|
| Last-reviewed date | Update it when the chapter is re-verified, not when it is edited. |
| Evidence legend | Keep the tags. A claim without a tag is a claim without evidence. |
| Source over memory | Re-read the file before changing a behavioural statement. |
| No secrets | Never add real env values, keys, or enquiry data. |
| Runtime claims | Do not upgrade 🟡 to ✅ without actually running the thing. |
| Stale docs | Record contradictions in [21 §6](21-known-issues-decisions-and-roadmap.md#6-stale-existing-documentation) rather than silently deleting history. |

---

## Document status

| Item | Value |
|---|---|
| Files in this set | **22** |
| Created | 2026-10-19 |
| Basis | Read-only source audit |
| Application code changed | **none** |
| Shell commands executed | **none** (shell unavailable) |
| Runtime verification | **none** |
