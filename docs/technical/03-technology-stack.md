# 03 — Technology Stack

> **Purpose:** State exactly what the application is built on, what each dependency is used for, and — equally important — what is *not* used despite being installed.
> **Audience:** New developers, reviewers, and procurement/security assessors.
> **Last reviewed:** 2026-10-24
> **Evidence basis:** `composer.json`, `package.json`, `vite.config.js`, `phpunit.xml`, `config/*.php`, `vendor/composer/installed.json` (package names only). No dependency audit or build was run.

**Verification legend:** ✅ source-verified · 🟡 runtime-unverified · ⚠️ partial · ⛔ not used/absent · ❓ unknown

---

## 1. Runtime stack

| Component | Declared constraint | Evidence | Status |
|---|---|---|---|
| PHP | `^8.2` | `composer.json:9` | ✅ |
| Laravel | `^12.0` | `composer.json:11` | ✅ |
| Filament | `~4.0` | `composer.json:10` | ✅ |
| Livewire | transitive via Filament | `vendor/composer/installed.json` entry present | ✅ present / ❓ exact version |
| Tinker | `^2.10.1` | `composer.json:12` | ✅ |

**Exact installed versions are unknown** — `composer.lock` was not read. One existing document claims "Filament v4.15.2" (`docs/2026-10-15-work-report.md:4`); this could not be confirmed. ❓

### 1.1 What Laravel features are actually used

| Feature | Used? | Where |
|---|---|---|
| Routing (web) | ✅ | `routes/web.php` |
| Routing (api) | ⛔ | no `routes/api.php` |
| Eloquent ORM | ✅ | 12 models |
| Query builder | ✅ | via Eloquent scopes |
| Migrations | ✅ | 16 files |
| Seeders & factories | ✅ | 10 seeders, `UserFactory` |
| Blade | ✅ | ~54 views |
| Middleware | ✅ | 2 custom + framework |
| Validation | ✅ | `$request->validate()` in `ContactController` |
| Form Requests | ⛔ | no `app/Http/Requests` |
| Service container / providers | ⚠️ | 2 providers, both effectively empty |
| Facades | ✅ | `Cache`, `Storage`, `Mail`, `File` |
| Cache | ✅ | `Settings::all()` only |
| Sessions | ✅ | admin login + contact flash |
| Queues | ⛔ | tables exist, **no jobs** |
| Events / Listeners | ⛔ | no `app/Events`, `app/Listeners` |
| Notifications | ⛔ | no `app/Notifications` |
| Mail (Mailable classes) | ⛔ | uses `Mail::send` with Blade views |
| Artisan commands | ✅ | 1 custom (`sitemap:generate`) |
| Task scheduling | ⛔ | nothing registered |
| Policies / Gates | ⛔ | none |
| Broadcasting | ⛔ | none |
| API resources | ⛔ | none |
| Localization | ⚠️ | locale `en` only; no `lang/` overrides |
| Testing | ✅ | PHPUnit 11, 11 files |

---

## 2. Admin stack

| Component | Detail |
|---|---|
| Panel provider | `app/Providers/Filament/AdminPanelProvider.php` |
| Panel id / path | `admin` / `/admin` (27-28) |
| Auth | `->login()` (29) — session guard, `users` provider |
| Colour | `Filament\Support\Colors\Color::Amber` (31) |
| Discovery | resources, pages, widgets auto-discovered (33-34, 49) |
| Nav groups | Content, Homepage Management, Settings, Operations (38-48) |
| Middleware | EncryptCookies, AddQueuedCookies, StartSession, AuthenticateSession, ShareErrors, VerifyCsrfToken, SubstituteBindings, DisableBladeIconComponents, DispatchServingFilamentEvent (50-60) |
| Auth middleware | `Filament\Http\Middleware\Authenticate` (61-63) |
| Admin gate | `User implements FilamentUser`; `canAccessPanel()` requires `is_admin = true` (`User.php:13,59-62`) |
| Extra plugins | **none** — no Shield, no Spatie Permission, no media library, no activity log |

Filament v4 API surface used: `Filament\Schemas\Schema`, `Filament\Schemas\Components\{Section, Tabs, Tab, Form, Actions, EmbeddedSchema}`, `Filament\Forms\Components\*`, `Filament\Tables\Columns\*`, `Filament\Actions\*`, `Filament\Infolists\Components\TextEntry`. ✅ (read in source; not rendered)

---

## 3. Front-end stack

| Component | Version constraint | Evidence |
|---|---|---|
| Vite | `^7.0.7` | `package.json:15` |
| laravel-vite-plugin | `^2.0.0` | `package.json:13` |
| Tailwind CSS | `^4.0.0` | `package.json:14` |
| `@tailwindcss/vite` | `^4.0.0` | `package.json:10` |
| axios | `^1.11.0` | `package.json:11` |
| concurrently | `^9.0.1` | `package.json:12` |

### 3.1 Tailwind v4 configuration

`resources/css/app.css:1-6`:

```css
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';
```

There is **no `tailwind.config.js`** — v4 uses CSS-first configuration. Brand tokens are declared in `@theme` (`app.css:8-…`):

| Token | Value |
|---|---|
| `--color-hansa-navy` | `#002e5b` |
| `--color-hansa-navy-dark` | `#001f3f` |
| `--color-hansa-blue` | `#6c98e1` |
| `--color-hansa-gold` | `#fcb000` |
| `--color-hansa-gold-dark` | `#b97c00` |
| `--color-hansa-yellow` | `#fde428` |

⚠️ The `@source '../**/*.js'` glob means class names inside JS strings are scanned; dynamic class construction will not be detected.

### 3.2 JavaScript

No framework, no bundler-specific runtime, no TypeScript. Five plain ES modules:

| File | Responsibility |
|---|---|
| `resources/js/app.js` | sticky header, mobile menu, hero rotator, carousels, slogan particles, count-up, scroll reveal, rail links, consent wiring, map wiring (469 lines) |
| `resources/js/consent.js` | consent banner, GA4 load/revoke, `_ga*` cookie deletion |
| `resources/js/office-map.js` | click-to-load map gate, 12 s slow-load hint |
| `resources/js/office-map-render.js` | iframe activation helper |
| `resources/js/bootstrap.js` | axios setup |

`axios` is imported but **no AJAX call exists** in application code — all forms are standard POSTs. ⚠️ Dead dependency.

### 3.3 Fonts and icons

- Self-hosted Avenir (`public/fonts/AvenirLTStd-Roman.woff`), preloaded on the homepage.
- Two legacy icon fonts: `stm93ff.woff`, `icomoon0b43.woff` — used by the `_services` cards. 🕓
- Filament ships its own Inter font set under `public/fonts/filament/`.
- Icons elsewhere are inline SVG (hand-written paths in `_policy`, `contact`, etc.) or Heroicons in Filament.

---

## 4. Build pipeline

`vite.config.js` uses `laravel-vite-plugin` with two entries: `resources/css/app.css` and `resources/js/app.js`.

| Artefact | Status |
|---|---|
| `public/build/manifest.json` | ✅ exists |
| `public/build/assets/app-BdKPwMxD.css` | ✅ exists |
| `public/build/assets/app-C4PxnG3f.js` | ✅ exists |
| `public/build` tracked in Git | ⛔ ignored (`.gitignore:17`) |
| `public/hot` (dev server marker) | ⛔ absent |

**Consequence:** a fresh clone has **no compiled assets**, and `@vite([...])` in `resources/views/layouts/app.blade.php:8` will throw `ViteManifestNotFoundException` until `npm install && npm run build` runs. This is a deployment prerequisite, documented in [20 — Deployment](20-deployment-and-operations.md).

---

## 5. Testing stack

| Item | Value |
|---|---|
| Runner | PHPUnit `^11.5.50` (`composer.json:22`) |
| Config | `phpunit.xml` |
| Suites | `Unit` (`tests/Unit`), `Feature` (`tests/Feature`) |
| DB | SQLite `:memory:` (26-27) |
| Cache / session | `array` (25, 31) |
| Mail | `array` (29) |
| Queue | `sync` (30) |
| Coverage source | `app/` (15-19) |
| Pest | ⛔ not installed — tests are plain PHPUnit classes with `#[DataProvider]` attributes |

Also installed but not exercised: Pint (formatting), Sail (Docker — no compose file inspected), Pail (log tailing), Boost, Collision, Faker, Mockery.

---

## 6. Dependency inventory and purpose

### 6.1 Production (`require`)

| Package | Purpose in this app |
|---|---|
| `php ^8.2` | runtime |
| `laravel/framework ^12.0` | framework |
| `filament/filament ~4.0` | admin panel |
| `laravel/tinker ^2.10.1` | console REPL; **no application code depends on it** |

### 6.2 Development (`require-dev`)

| Package | Purpose | Used by app? |
|---|---|---|
| `phpunit/phpunit` | tests | ✅ |
| `fakerphp/faker` | `UserFactory` | ✅ seeders |
| `mockery/mockery` | test doubles | ⚠️ not used directly in the 11 test files read |
| `nunomaduro/collision` | CLI error output | ✅ framework |
| `laravel/pint` | code style | ⚠️ no CI runs it |
| `laravel/sail` | Docker dev | ❓ no compose file inspected |
| `laravel/pail` | log viewer | ✅ `composer dev` script |
| `laravel/boost` | AI/dev tooling | ❓ not referenced in code |

### 6.3 Composer scripts

| Script | Actions |
|---|---|
| `setup` (37-44) | `composer install` → copy `.env` → `key:generate` → `migrate --force` → `npm install` → `npm run build` |
| `dev` (45-48) | concurrently: `artisan serve`, `queue:listen`, `pail`, `npm run dev` |
| `test` (49-52) | `config:clear` → `artisan test` |
| `post-autoload-dump` (53-57) | package discovery + `filament:upgrade` |
| `post-create-project-cmd` (64-68) | key generate, create SQLite file, `migrate --graceful` |

⚠️ `setup` runs `migrate --force` but **not** `db:seed` and **not** `storage:link`.

---

## 7. What is deliberately absent

| Absent | Implication |
|---|---|
| API layer | No mobile app, no SPA, no external consumer |
| Queue workers in practice | `composer dev` starts `queue:listen`, but nothing dispatches |
| Scheduler | Sitemap must be regenerated manually |
| Redis / Memcached | Cache and sessions are database-backed |
| Octane | `ImageUrl::$memo` is safe per-process (see `ImageUrl.php:27-31`) |
| Docker/CI | Deployment is manual |
| CDN | Assets served from origin |
| Error tracking | No Sentry/Telescope/Nightwatch |
| Search | No Scout/Meilisearch; only Filament table search |
| Multi-language | Single locale `en` |
| Multi-tenancy | Single site |

---

## Known limitations

- Exact package versions unverified (`composer.lock` not read).
- `npm run build` was not executed; the committed manifest could be stale relative to `resources/`.
- Filament v4 was read at source level only; no panel was rendered.
- Whether the production host runs `npm run build` or ships prebuilt assets is unknown.

---

## Related chapters

- [02 — Architecture & Request Lifecycle](02-architecture-and-request-lifecycle.md)
- [04 — Local Development Setup](04-local-development-setup.md)
- [05 — Configuration Reference](05-configuration-reference.md)
- [18 — Frontend Assets & Accessibility](18-frontend-assets-and-accessibility.md)
- [19 — Testing & QA](19-testing-and-qa.md)
- [20 — Deployment & Operations](20-deployment-and-operations.md)
