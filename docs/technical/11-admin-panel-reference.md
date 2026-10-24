# 11 — Admin Panel Reference

> **Purpose:** Document the Filament v4 admin panel: access, the `is_admin` gate, navigation, all 10 resources, the custom settings page, the dashboard widget, and the remaining authorization gaps.
> **Audience:** Administrators, developers, and security reviewers.
> **Last reviewed:** 2026-10-24
> **Evidence basis:** `app/Providers/Filament/AdminPanelProvider.php`, all 10 resources under `app/Filament/Resources/`, all **29** resource page classes (9 resources × list/create/edit + 2 for the read-only enquiry resource), `app/Filament/Pages/ManageSettings.php`, `app/Filament/Widgets/DashboardStats.php`, `config/auth.php`, `app/Models/User.php`, `database/migrations/2026_10_24_000001_add_is_admin_to_users_table.php`, `app/Console/Commands/CreateAdmin.php`, `tests/Feature/Admin/`. The panel was **not rendered** (no shell/browser).

**Verification legend:** ✅ source-verified · 🟡 runtime-unverified · ⚠️ partial · ⛔ missing · ❓ unknown

---

## 1. Access

| Item | Value | Evidence |
|---|---|---|
| URL | `/admin` | `AdminPanelProvider.php:28` |
| Panel id | `admin` | 27 |
| Login page | enabled, `/admin/login` | 29 |
| Registration | ⛔ disabled | no `->registration()` |
| Password reset | ⛔ disabled | no `->passwordReset()` |
| Profile page | ⛔ disabled | no `->profile()` |
| 2FA | ⛔ absent | no MFA config |
| Colour | Amber | 31 |
| Auth guard | `web` (session, Eloquent `users`) | `config/auth.php:40-45` |
| Auth middleware | `Filament\Http\Middleware\Authenticate` | `AdminPanelProvider.php:61-63` |
| Admin gate | `User implements FilamentUser`; `canAccessPanel()` returns `true` only for the `admin` panel **and** `is_admin = true` | `app/Models/User.php:13,59-62` |
| Admin flag | `users.is_admin` boolean, `NOT NULL DEFAULT 0` | `database/migrations/2026_10_24_000001_add_is_admin_to_users_table.php` |
| Account creation | `php artisan admin:create` (local only) or a deliberate `tinker` update; **no UI** | `app/Console/Commands/CreateAdmin.php` |

**Who can log in:** any row in `users` with a password can authenticate, but the panel middleware additionally requires `is_admin = true`. A logged-in non-admin is rejected with **HTTP 403**; a guest is redirected to `/admin/login`. `is_admin` is deliberately **not** mass assignable. Accounts are created only by `admin:create` (local development), `tinker`, or `UserFactory` in tests — there is still **no user-management UI**. ⛔

The check is independent of `APP_ENV`: `APP_ENV=local` does **not** grant panel access to ordinary accounts.

```bash
php artisan migrate          # adds users.is_admin (default 0)
php artisan admin:create     # interactive; refuses outside APP_ENV=local
```

---

## 2. Authorization reality

This section is deliberately blunt because it is the panel's largest remaining risk.

| Control | Status |
|---|---|
| Authentication | ✅ Filament login |
| Panel access gate | ✅ `FilamentUser::canAccessPanel()` requires `is_admin` |
| Role-based access control | ⛔ **none** — the flag is binary |
| Policies | ⛔ no `app/Policies` directory |
| Gates | ⛔ no `Gate::define` anywhere in `app/` |
| `authorize()` / `can()` calls | ⛔ none |
| Per-resource permission checks | ⛔ none |
| Field-level permissions | ⛔ none |
| User management | ⛔ none (CLI only) |
| Audit log | ⛔ none |
| IP allow-list | ⛔ none |
| 2FA | ⛔ none |

**Consequences:**

1. Any account with `is_admin = true` can read every enquiry and change or delete any content.
2. A marketing editor cannot be given access without also giving them enquiry PII.
3. A compromised admin account has full control of public content and all stored enquiries.
4. There is no record of who changed or deleted what.

The `is_admin` gate answers **"may this person enter `/admin` at all?"** It does **not** answer "may this person see enquiries but not edit content?". Every admin is still all-powerful once inside.

Severity: **Medium** for panel access (previously High), **High** for the absence of per-resource controls whenever more than one person holds `is_admin`. Mitigations available today are operational (keep the admin list minimal, strong unique passwords, restrict `/admin` by IP/proxy).

**Proper fix (not applied):** add roles/policies or a permission package, and gate `ContactSubmissionResource` and destructive actions.

---

## 3. Navigation

`AdminPanelProvider.php:38-48` declares four groups:

| Group | Resources (sort) |
|---|---|
| **Content** | Insights (1), Projects (2), Gallery (3), Team (4) |
| **Homepage Management** | Hero Slider (1), Valued Clients (2), Business Concerns (3), Homepage Sections (4) |
| **Settings** | Site Settings (1), Offices (2) |
| **Operations** | Contact Submissions (1) |

Plus the Dashboard (`Filament\Pages\Dashboard`, line 36).

⚠️ One prior document (`docs/admin-panel-audit.md:277-278`) states Contact Submissions sits *outside* the navigation groups. That is **outdated** — `ContactSubmissionResource.php:26` sets `$navigationGroup = 'Operations'`.

---

## 4. Dashboard

`app/Filament/Widgets/DashboardStats.php:15-58` — five `StatsOverviewWidget` stats:

| Stat | Query | Colour |
|---|---|---|
| Active Projects | `Project::where('is_active', true)->count()` | success |
| Active Team Members | `TeamMember::where('is_active', true)->count()` | info |
| Active Hero Slides | `HeroSlide::where('is_active', true)->count()` | primary |
| Published Posts | `Post::published()->count()` | success |
| Unread Messages | `ContactSubmission::whereNull('read_at')->count()` | warning |

No chart widgets, no "recent activity", no quick links. ⛔

---

## 5. Resource summary

| # | Resource | Model | Table | Group / sort | Pages | Upload dir | Create |
|---|---|---|---|---|---|---|---|
| 1 | `PostResource` | `Post` | `posts` | Content / 1 | L, C, E | `posts`, `posts/og` | ✅ |
| 2 | `ProjectResource` | `Project` | `projects` | Content / 2 | L, C, E | `projects` | ✅ |
| 3 | `GalleryResource` | `GalleryImage` | `gallery_images` | Content / 3 | L, C, E | `gallery` | ✅ |
| 4 | `TeamMemberResource` | `TeamMember` | `team_members` | Content / 4 | L, C, E | `team` | ✅ |
| 5 | `HeroSlideResource` | `HeroSlide` | `hero_slides` | Homepage Mgmt / 1 | L, C, E | `hero-slides` | ✅ |
| 6 | `ClientResource` | `Client` | `clients` | Homepage Mgmt / 2 | L, C, E | `clients` | ✅ |
| 7 | `ConcernResource` | `Concern` | `concerns` | Homepage Mgmt / 3 | L, C, E | `concerns` | ✅ |
| 8 | `HomepageSectionItemResource` | `HomepageSectionItem` | `homepage_section_items` | Homepage Mgmt / 4 | L, C, E | `homepage` | ✅ |
| 9 | `OfficeResource` | `Office` | `offices` | Settings / 2 | L, C, E | — | ✅ |
| 10 | `ContactSubmissionResource` | `ContactSubmission` | `contact_submissions` | Operations / 1 | L, V | — | ⛔ |

L = list, C = create, E = edit, V = view.

All resources share the same action set unless noted: **Edit**, **Delete**, and a **bulk Delete** group. All list tables are sortable/searchable as configured, and most are `->reorderable('<sort column>')` for drag ordering.

---

## 6. Content resources

### 6.1 Insights — `/admin/posts`

Full field/validation detail in [09 — Insights Subsystem](09-insights-subsystem.md#6-admin-editing--adminposts).

Highlights: slug auto-generated on create only; `body` is a `RichEditor` and is rendered unescaped on the public site; `published_at` in the future hides the article.

### 6.2 Projects — `/admin/projects`

`ProjectResource.php` (179 lines).

**Section "Project"** (44-100):

| Field | Rules |
|---|---|
| `title` | required, max 255 |
| `location` | required, max 255 |
| `category` | select from `Project::CATEGORIES` (`ongoing`, `upcoming`), default `ongoing`, required |
| `sort` | integer ≥ 0, default 0, required |
| `image` | image, `projects/`, jpeg/png/webp, max 3 MB |
| `image_alt` | max 255 |
| `is_active` | toggle, default true |

**Section "Homepage card"** (101-127): `is_featured`, `homepage_title`, `homepage_location`, `homepage_alt`, `homepage_sort`.

Table (131-169): image preview, title (searchable), category badge, location (hidden), sort, homepage toggle, active toggle. Default sort `sort`, reorderable. ⚠️ `->reorderable('sort')` plus a manual `sort` field means two ways to set the same value.

Public effect: Projects page order and the homepage band — see [07](07-public-pages-reference.md#7-projects) and [08](08-homepage-composition.md#39-featured-projects-_projectsbladephp).

### 6.3 Gallery — `/admin/galleries`

`GalleryResource.php` (132 lines).

| Field | Rules |
|---|---|
| `image` | image, `gallery/`, max 3 MB, **not required** |
| `caption` | required, max 255 — also the alt text |
| `sort` | integer ≥ 0, required |
| `is_active` | toggle |

Table: photo preview, caption (searchable), sort, active, updated (hidden). Default sort `sort`, reorderable.

⚠️ `image` is nullable in both the schema and the form, so a photo can be saved with no file; the public grid then emits an empty `src`.

### 6.4 Team — `/admin/team-members`

`TeamMemberResource.php` (175 lines). Explicit `$slug = 'team-members'` (32).

| Field | Rules |
|---|---|
| `name` | required, max 255, live → slug on create |
| `role` | required, max 255 |
| `slug` | required, max 100, **unique**, internal dialog anchor |
| `modal_name` | max 255 |
| `sort` | integer ≥ 0, required |
| `teaser` | required, rows 3 |
| `bio` | required, rows 6 |
| `image` | image, `team/`, max 3 MB |
| `image_alt` | max 255 |
| `is_active` | toggle |

Public effect: `/management` flip cards + `<dialog>` bios.

⚠️ Slug is user-editable and used as the dialog `id`, so changing it breaks any external deep link to `#chairman-message` etc.

### 6.5 Hero Slider — `/admin/hero-slides`

`HeroSlideResource.php` (130 lines).

| Field | Rules |
|---|---|
| `image` | **required**, image, `hero-slides/`, max **2 MB** |
| `alt` | required, max 255 |
| `label` | max 255, admin-only |
| `sort_order` | integer ≥ 0, required |
| `is_active` | toggle |

Note the **2 MB** limit here vs 3 MB elsewhere — a deliberate difference (hero images are large and above the fold) but easy to miss.

### 6.6 Valued Clients — `/admin/clients`

`ClientResource.php` (124 lines).

| Field | Rules |
|---|---|
| `image` | **required**, `clients/`, max **2 MB** |
| `name` | required, max 255 |
| `alt` | required, max 255 |
| `sort_order` | integer ≥ 0, required |
| `is_active` | toggle |

### 6.7 Business Concerns — `/admin/concerns`

`ConcernResource.php` (124 lines). Identical field set and rules to Clients, upload directory `concerns/`.

### 6.8 Homepage Sections — `/admin/homepage-section-items`

`HomepageSectionItemResource.php` (202 lines). Explicit `$slug = 'homepage-section-items'` (33). The most conditional form in the panel.

| Field | Visibility | Rules |
|---|---|---|
| `section` | always | select from `HomepageSectionItem::SECTIONS`, required, `live()` |
| `title` | `commitments`, `glance`, `numbers` | max 255; label changes to "Label under the number" for `numbers` |
| `value` | `numbers` | max 50, required for `numbers` |
| `value_from_year` | `numbers` | integer 1900-2100 |
| `body` | `commitments`, `glance`, `why` | label "Bullet point" for `why` |
| `group` | `why` | `1`/`2`, default `1` |
| `icon` | `commitments` | select from `HomepageSectionItem::ICONS` |
| `image` | `commitments`, `glance` | `homepage/`, max 3 MB |
| `image_alt` | `commitments`, `glance` | max 255 |
| `sort` | always | integer ≥ 0, required |
| `is_active` | always | toggle |

Table: image, section badge (label formatted from `SECTIONS`), heading, `display_value`, sort, active. Filter by section. Default sort `sort` — **not** reorderable (drag reorder is absent here, unlike the other resources).

⚠️ Field visibility is driven by `Get $get`, evaluated client-side by Livewire. Switching section after typing can leave hidden values persisted; the controller reads them by key regardless.

---

## 7. Offices — `/admin/offices`

`OfficeResource.php` (150 lines).

| Field | Rules |
|---|---|
| `title` | required, max 255 |
| `address` | required, textarea rows 2 |
| `phone` | tel, max 30 |
| `landline` | max 50 |
| `email` | email, max 190 |
| `sort` | integer ≥ 0, required |
| `is_active` | toggle |
| `latitude` | numeric, -90…90 |
| `longitude` | numeric, -180…180 |

The form's map section carries a long warning (80) that the legacy coordinates were placeholders and must not be reused.

Public effect: contact page cards and the office map. An office appears on the map **only** when both coordinates are present and in range (`OfficeMap::isValidPair`, 81-92).

⚠️ Coordinates are not cross-validated against the address; a typo inside the valid range still places a marker somewhere on Earth.

---

## 8. Contact Submissions — `/admin/contact-submissions`

`ContactSubmissionResource.php` (109 lines). Full detail in [10 — Contact & Enquiries](10-contact-and-enquiries.md#8-admin-inbox--admincontact-submissions).

Summary: view-only resource, no create, no edit, mark read/unread, delete, bulk delete, searchable name/email, default sort newest first.

---

## 9. Site Settings — `/admin/settings`

`app/Filament/Pages/ManageSettings.php` (slug `settings`, group Settings, sort 1). Documented in full in [12 — Site Settings](12-site-settings.md).

---

## 10. Shared upload pattern

Every image field in the panel follows the same shape:

```php
FileUpload::make('image')
    ->image()
    ->disk('public')
    ->directory('<per-resource>')
    ->visibility('public')
    ->acceptedFileTypes(['image/jpeg','image/png','image/webp'])
    ->maxSize(<2048|3072>)
    ->imagePreviewHeight('200')
    ->fetchFileInformation(false)
    ->getUploadedFileUsing(fn (string $file): ?array => [
        'name' => basename($file),
        'size' => 0,
        'type' => null,
        'url'  => ImageUrl::resolve($file),
    ])
```

Purpose: allow a single field to hold either a `public/`-relative legacy path or a `storage/app/public/` upload path, and preview both. See [14 — Media](14-media-and-image-pipeline.md#3-the-shared-upload-pattern).

⚠️ Consequence: files are written to `storage/app/public/<dir>`, which is only web-reachable through the missing `public/storage` symlink.

---

## 11. What the panel cannot do

| Capability | Status |
|---|---|
| Manage users / invite editors | ⛔ (create via `admin:create` / tinker only) |
| Grant or revoke `is_admin` | ⛔ (CLI only; `is_admin` is not mass assignable) |
| Reset a password | ⛔ (no UI; must use tinker/CLI) |
| Assign roles | ⛔ |
| View an audit trail | ⛔ |
| Restore a deleted record | ⛔ (hard delete, no soft deletes) |
| Schedule a post (actively) | ⚠️ passive: future `published_at` hides it, no job runs |
| Preview an unpublished post as a visitor | ⛔ |
| Reorder by drag on Homepage Sections | ⛔ (numeric only) |
| Edit any static page copy | ⛔ |
| Edit service page copy | ⛔ |
| Edit careers copy | ⛔ |
| Upload multiple images at once | ⚠️ per-field only |
| Bulk edit | ⛔ (bulk delete only) |

---

## 12. Testing coverage

| Area | Tests |
|---|---|
| Admin login | ✅ `AdminAccessTest` — guest redirect, login page reachable |
| Authorization | ✅ `AdminAccessTest` — non-admin 403, admin 200, local-env denial, mass assignment |
| Admin command | ✅ `CreateAdminCommandTest` — local-only guard, creation, password mismatch, existing email |
| Any resource CRUD | ⛔ |
| Upload handling | ⛔ |
| Dashboard counts | ⛔ |

⚠️ These tests were **authored but not executed** in the documentation environment (shell unavailable). Their pass/fail status is unverified; see [19 — Testing & QA](19-testing-and-qa.md#4-coverage-map).

The panel's **access gate** is covered. Resource CRUD, uploads and dashboard counts remain untested.

---

## Known limitations

- The panel was not rendered; field behaviour (conditional visibility, live slug generation) is source-derived.
- Filament v4 exact minor version unknown.
- Generated route slugs for resources without an explicit `$slug` are inferred, not observed.
- Whether the deployed `users` table has been migrated (`is_admin`) and which rows carry `is_admin = true` is unknown (no production DB access).
- No screenshot or browser verification of the UI exists.

---

## Related chapters

- [06 — Routing & URL Map](06-routing-and-url-map.md#5-admin-routes)
- [09 — Insights Subsystem](09-insights-subsystem.md)
- [10 — Contact & Enquiries](10-contact-and-enquiries.md)
- [12 — Site Settings](12-site-settings.md)
- [13 — Database & Data Dictionary](13-database-and-data-dictionary.md)
- [14 — Media & Image Pipeline](14-media-and-image-pipeline.md)
- [17 — Security & Hardening](17-security-and-hardening.md)
- [21 — Known Issues](21-known-issues-decisions-and-roadmap.md)
