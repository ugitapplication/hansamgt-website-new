# 04 — Local Development Setup

> **Purpose:** Get a working local copy of the site from a fresh clone, with the exact commands and the known traps.
> **Audience:** Developers onboarding to this project.
> **Last reviewed:** 2026-10-24
> **Evidence basis:** `composer.json` scripts, `package.json`, `.env.example`, `phpunit.xml`, `.gitignore`, `config/*.php`, `bootstrap/app.php`. **No command in this chapter was executed** — the shell is unavailable in the audit environment (exit code 66).

**Verification legend:** ✅ source-verified · 🟡 runtime-unverified · ⚠️ partial · ⛔ missing · ❓ unknown

---

## 1. Prerequisites

| Requirement | Version | Why |
|---|---|---|
| PHP | **8.2+** | `composer.json:9` |
| PHP extensions | `pdo_sqlite`, `mbstring`, `openssl`, `fileinfo`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath` | Laravel + Filament + SQLite |
| Composer | 2.x | |
| Node.js | 20+ (Vite 7 requirement) | `package.json:15` |
| npm | bundled with Node | |
| SQLite | 3.x (bundled with PHP) | default database |

⚠️ `fileinfo` matters for upload validation — Filament's `image()` rule depends on it. A host without it weakens upload checks.

⚠️ Vite 7 requires Node 20.19+ or 22.12+. An older Node will fail at `npm install`.

---

## 2. Quick start

The intended path is the Composer `setup` script (`composer.json:37-44`):

```bash
composer setup
```

which runs, in order:

1. `composer install`
2. copy `.env.example` → `.env` if absent
3. `php artisan key:generate`
4. `php artisan migrate --force`
5. `npm install`
6. `npm run build`

### 2.1 What `setup` does *not* do

| Missing step | Consequence |
|---|---|
| `php artisan db:seed` | no content — homepage falls back to hard-coded Blade, admin lists are empty |
| `php artisan storage:link` | uploaded images 404 |
| create `database/database.sqlite` | `migrate` cannot connect; in interactive mode Laravel offers to create the file, but `setup` runs `migrate --force`, so it fails instead |

⚠️ **This is a real gap.** `post-create-project-cmd` (64-68) creates the SQLite file with `touch`, but `setup` does not. On a fresh clone where `database/database.sqlite` is absent, the non-interactive `migrate --force` in `setup` has no database to open. The file is also **not** covered by this repo's `.gitignore` (see §3.3), so its presence on a given machine is unknown until checked. 🟡 Exact CLI output not observed — shell unavailable.

**Verified safe manual sequence:**

```bash
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
# ensure database/database.sqlite exists:
#   Windows: type nul > database\database.sqlite
#   Unix:    touch database/database.sqlite
php artisan migrate
php artisan db:seed           # optional but recommended for content
php artisan admin:create      # create the local administrator (§5)
php artisan storage:link
npm install
npm run build
php artisan serve
```

Then open `http://127.0.0.1:8000`.

### 2.2 Development runner

```bash
composer dev
```

Runs four processes concurrently (`composer.json:45-48`):

| Process | Command | Purpose |
|---|---|---|
| server | `php artisan serve` | app at :8000 |
| queue | `php artisan queue:listen --tries=1 --timeout=0` | ⚠️ nothing dispatches jobs |
| logs | `php artisan pail --timeout=0` | live log tail |
| vite | `npm run dev` | HMR at :5173 |

⚠️ The queue worker is idle — there are no jobs. Harmless but misleading.

⚠️ `pail` is a dev-only package; it is in `require-dev` and installed.

---

## 3. Environment file

`.env.example` (65 lines) is the only environment template. **The real `.env` was not read** (secrets).

### 3.1 Values that must change locally

| Key | Example value | Change to |
|---|---|---|
| `APP_NAME` | `Laravel` | `HANSA Management` (affects mail from-name and session cookie name) |
| `APP_URL` | `http://localhost` | `http://127.0.0.1:8000` or your valet/homestead host |
| `APP_KEY` | empty | run `key:generate` |
| `DB_CONNECTION` | `sqlite` | keep for local |
| `MAIL_MAILER` | `log` | keep for local — mail goes to `storage/logs/laravel.log` |

### 3.2 Values that are fine locally

`APP_ENV=local`, `APP_DEBUG=true`, `LOG_LEVEL=debug`, `SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=database`, `FILESYSTEM_DISK=local`.

⚠️ `APP_DEBUG=true` locally is correct. It must be `false` in production (see [20](20-deployment-and-operations.md#5-environment-checklist)).

### 3.3 Database file

`DB_CONNECTION=sqlite` with no `DB_DATABASE` resolves to `database_path('database.sqlite')` (`config/database.php:38`).

⚠️ `database/database.sqlite` is **not** in this repo's `.gitignore` (lines 1-29 contain no database entry). If a developer commits it, real enquiry data could enter Git history. **Never commit it.** Verify with `git check-ignore database/database.sqlite`; if it is not ignored, add it — but that is a code change and outside this documentation task.

---

## 4. Seeding

```bash
php artisan db:seed
```

Runs `DatabaseSeeder` (see [13 §6](13-database-and-data-dictionary.md#6-seeders)):

- seeds 7 hero slides, 18 clients, 13 concerns, 4 offices, 4 articles, 11 projects, 12 photos, 4 team members, and all 4 homepage sections
- creates **no** user account

Administrators are created separately — see §5.

### 4.1 Re-seeding is safe

`DatabaseSeeder` no longer inserts a user, so the old duplicate-`users.email` failure is gone (fixed 2026-10-24). Every content seeder uses `updateOrCreate` / `firstOrNew`, so a second `php artisan db:seed` converges instead of duplicating rows.

⚠️ `migrate:fresh` is still destructive. Never run it against a database with real enquiries.

---

## 5. Admin access

Admin access requires **authentication plus `is_admin = true`**. A fresh database has no administrators.

1. Apply the migration (adds `users.is_admin`, default `0`):

```bash
php artisan migrate
```

2. Create a local administrator interactively:

```bash
php artisan admin:create
```

Enter a name, email, a password of at least 12 characters, and the same password again. The command hashes the password and sets `is_admin = true`.

3. Visit `http://127.0.0.1:8000/admin` and log in.

Existing users are **not** promoted — the migration sets every existing row to `is_admin = 0`. To deliberately promote an existing local account:

```bash
php artisan tinker --execute="App\Models\User::where('email','you@hansamgt.com')->update(['is_admin' => true]);"
```

⚠️ `admin:create` refuses to run outside `APP_ENV=local`. There is **no** registration, password-reset, or user-management UI. Accounts are CLI-only. See [11 §1](11-admin-panel-reference.md#1-access).

⚠️ `is_admin` is not mass assignable, so `User::create([... 'is_admin' => true])` silently ignores the flag. Use `forceFill` or the `update()` call above.

---

## 6. Verifying the install

| Check | Expected |
|---|---|
| `http://127.0.0.1:8000/` | homepage with hero, sections, footer |
| `http://127.0.0.1:8000/up` | 200, empty body |
| `http://127.0.0.1:8000/admin` | redirect to `/admin/login` |
| Logged in without `is_admin` | **403** on `/admin` and every panel route |
| Logged in with `is_admin` | dashboard loads |
| `http://127.0.0.1:8000/sitemap.xml` | XML with 20 URLs (committed file) |
| `http://127.0.0.1:8000/robots.txt` | text |
| `php artisan test` | see [19](19-testing-and-qa.md) |
| `http://127.0.0.1:8000/storage/...` | ⚠️ 404 until `storage:link` |

### 6.1 If assets are missing

Symptom: `ViteManifestNotFoundException` or an unstyled page.

Cause: `public/build` is git-ignored and absent on a fresh clone.

Fix:

```bash
npm install
npm run build      # or: npm run dev, and keep it running
```

If `npm run dev` is running, `public/hot` exists and `@vite` points at `:5173`. That is correct. Do not run `build` while `dev` is running.

---

## 7. Local traps

| Trap | Explanation |
|---|---|
| Missing SQLite file | `migrate` fails; create the file first |
| Missing `storage:link` | every uploaded image 404s |
| No build output | `@vite` throws |
| Re-seeding | unique email violation |
| `APP_ENV=local` + CSP | Vite origins are added to the CSP only in `local`; if you set `APP_ENV=development`, HMR may be blocked |
| `config:cache` left over | stale config ignores `.env` edits; run `php artisan config:clear` |
| Database cache/session | clearing the DB wipes sessions; you will be logged out |
| Queue worker | `composer dev` starts one, but nothing uses it |
| Test DB | tests use in-memory SQLite; they never touch your dev data ✅ |

---

## 8. IDE and tooling

| Tool | Config present? |
|---|---|
| Pint | ✅ `require-dev`, no `pint.json` (defaults) |
| PHPUnit | ✅ `phpunit.xml` |
| Larastan / PHPStan | ⛔ not installed |
| ESLint / Prettier | ⛔ not installed |
| EditorConfig | ⛔ absent |
| `.vscode` / `.idea` | ⛔ ignored |
| Debugbar / Telescope | ⛔ not installed |
| Xdebug | ❓ no config in repo |

⚠️ No static analysis, no JS linting, no formatting check in CI (there is no CI). Code style depends on contributors running `./vendor/bin/pint` manually.

---

## 9. Useful commands

| Task | Command |
|---|---|
| Clear all caches | `php artisan optimize:clear` |
| Rebuild caches (prod) | `php artisan optimize` |
| Regenerate sitemap | `php artisan sitemap:generate` |
| List routes | `php artisan route:list` |
| Tail logs | `php artisan pail` |
| Format PHP | `./vendor/bin/pint` |
| Run tests | `php artisan test` |
| Fresh DB + seed | `php artisan migrate:fresh --seed` ⚠️ destructive |
| Create storage link | `php artisan storage:link` |
| Tinker | `php artisan tinker` |

---

## Known limitations

- **No command in this chapter was run.** The shell in the audit environment exits with code 66 for every invocation, including `php -v`. The commands are standard Laravel/Composer commands verified against `composer.json` and the framework's documented behaviour, but the exact behaviour on this machine (Laragon on Windows) is unverified.
- The real `.env` was not read, so no statement about the current local configuration is possible.
- Whether `database/database.sqlite` currently exists and is populated is unknown (not opened).
- Node/PHP versions installed on this machine are unknown.

---

## Related chapters

- [03 — Technology Stack](03-technology-stack.md)
- [05 — Configuration Reference](05-configuration-reference.md)
- [13 — Database & Data Dictionary](13-database-and-data-dictionary.md#6-seeders)
- [14 — Media & Image Pipeline](14-media-and-image-pipeline.md#4-serving-files)
- [19 — Testing & QA](19-testing-and-qa.md)
- [20 — Deployment & Operations](20-deployment-and-operations.md)
