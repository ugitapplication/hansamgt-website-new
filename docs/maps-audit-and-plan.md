# Office map — audit and implementation plan

Status: **implemented, intentionally disabled.** No office has a verified
coordinate pair, so the map renders nothing on the public site. The wiring is in
place and switches itself on the moment verified coordinates are entered.

Date: 2026-10-18

---

## 1. Existing implementation findings

### 1.1 The legacy (CodeIgniter) site

The old site loaded the **Google Maps JavaScript API** globally, on every page,
from `hansamgtWebsite_old/application/views/frontend/Footer.php`. The map itself
lived in `.../frontend/Map.php`, with markers assembled from the office list in
`.../frontend/Address.php`. Marker positions were read out of the currently
active slide of an Owl Carousel (`.owl-item.active .item`), with info windows
matched back to markers by latitude.

Two things were wrong with it:

1. The API key baked into the template was no longer valid, so the browser
   rendered the "This page can't load Google Maps correctly" overlay instead of
   a map. The key is still present in the legacy repository and must be treated
   as compromised (see §1.3).
2. The coordinates in `Address.php` were **placeholders, not real locations**.

### 1.2 The new (Laravel) site

There is no map anywhere in the new project — not in
`resources/views/pages/home/sections/_offices.blade.php`, not in
`resources/views/pages/contact.blade.php`, and not in any partial. Office data
is already centralised:

- `app/Http/Controllers/HomeController::offices()` returns one array, used by
  both the homepage section and the contact page.
- `app/Models/Office.php`, the `offices` table and
  `app/Filament/Resources/Offices/OfficeResource.php` provide the admin UI.

There was therefore a single data source to hang a map off, and no legacy
markup to preserve.

### 1.3 The exposed legacy key

The legacy project contains a hardcoded Google Maps API key in its templates.
It is treated as compromised:

- it is **not** reused, copied, printed or referenced anywhere in this project,
  its configuration, its documentation or its tests;
- it must be **revoked/rotated by its owner in Google Cloud Console**, and its
  usage reviewed. Nothing in this repository can do that.

This implementation needs **no API key at all**.

---

## 2. Why the legacy coordinates cannot be trusted

`hansamgtWebsite_old/application/views/frontend/Address.php` contains four
office entries with `data-lat` / `data-lng` attributes. They are not evidence of
where the buildings are:

- Three of the four entries share **one identical coordinate pair**, despite
  being in three different parts of Dhaka (Banani, Eskaton and Uttara).
- The fourth differs only in the second decimal of both values.
- All four carry the same `data-title="Unique Group"`, so the map never
  distinguished one office from another.

These are template placeholders. Copying any of them into the new site would
publish a confident-looking but wrong location, so none of them were used, and
none were "cleaned up" into something that looks plausible.

---

## 3. Coordinate verification status

The four offices and the address published for each (from
`database/seeders/OfficeSeeder.php`, matching the site copy):

| # | Office | Address as published | Coordinates in repo | Verified? |
|---|--------|----------------------|---------------------|-----------|
| 1 | Corporate Office | Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani, Dhaka-1213 | none | **No** |
| 2 | Service Booth (Midtown) | Unique Heights, 117 Kazi Nazrul Islam Avenue, Eskaton, Dhaka | none | **No** |
| 3 | Service Booth (Uttara) | HANSA—A Premium Residence, House # 3 & 5, Road # 10/A, Sector # 09, Uttara, Dhaka-1230 | none | **No** |
| 4 | Service Booth (Dhanmondi) | Unique Trade Centre, 8 Panthapath, Kawranbazar, Dhaka-1215 | none | **No** |

No migration, seeder, model, controller, config value, fixture or document in
this repository contains a coordinate for any of these four sites, and no
authoritative source for them is bundled with the project. **Nothing here was
geocoded, inferred from an address, or approximated** — the map is disabled
until a human supplies checked values.

What is needed from the company: a WGS 84 latitude/longitude for each office,
taken from the actual building (for example by right-clicking the exact spot in
Google Maps or OpenStreetMap and copying the pair), with confirmation of which
entrance or block is intended where a site has several.

---

## 4. Architecture

**Leaflet + OpenStreetMap, click-to-load.**

| Piece | Location |
|-------|----------|
| Reusable component | `resources/views/partials/office-map.blade.php` |
| Marker/payload rules | `app/Support/OfficeMap.php` |
| Click-to-load gate | `resources/js/office-map.js` |
| Leaflet renderer (on demand) | `resources/js/office-map-render.js` |
| Styles | `resources/css/app.css` (search for "Office map") |
| Configuration | `config/site.php` → `maps` |

### 4.1 Data flow

The component is rendered with the *same* `$offices` array the office cards use
(`@include('partials.office-map', ['offices' => $offices ?? []])`), so there is
no second, hardcoded copy of the office list. `OfficeMap::markers()` keeps only
offices whose latitude **and** longitude are both present and in range, and the
result is emitted once, as a non-executable JSON data block:

```html
<script type="application/json" data-office-map-data>{ … }</script>
```

The front-end reads that block; it is a data island, not a script, so it is not
subject to `script-src` and needs no inline script or nonce.

### 4.2 Click-to-load

Before activation the component shows a bordered panel with a real
`<button type="button">Load interactive map</button>`, a short note that loading
the map contacts OpenStreetMap, and a **reserved height** (`clamp(280px, 45vw,
420px)`) so revealing the canvas cannot shift the page.

`resources/js/office-map.js` does nothing but wire that button. On click it
dynamically imports `./office-map-render`, which is the only module that imports
`leaflet` and `leaflet/dist/leaflet.css`. Until that moment Vite has not
requested Leaflet, its stylesheet, its marker images or a single map tile.

After activation the renderer:

- creates one map, adds a tile layer with the configured attribution;
- adds a marker per verified office, with the office name and address in the
  popup plus "Get directions" (keyless Google Maps deep link) and "View on
  OpenStreetMap" links;
- fits the viewport to the whole marker set (`fitBounds`, capped at zoom 16), so
  multiple offices appear together;
- disables `scrollWheelZoom` (the page never gets scroll-jacked) and disables
  Leaflet's zoom/fade/marker animations when `prefers-reduced-motion: reduce`
  is set;
- calls `invalidateSize()` on the next frame, so a container measured before
  layout settled is corrected without moving any marker.

### 4.3 Failure and no-JS behaviour

- No JavaScript: the panel is plain markup with a button that does nothing; no
  map, no tiles, no Leaflet. The office cards still list every address.
- Import or tile failure: the panel returns, the button re-enables, the status
  region reports "The interactive map could not be loaded.", and a fallback
  block with a link per verified office becomes visible, so there is always a
  way to reach the location.

### 4.4 Multiple instances

Each instance gets its own random DOM id (`office-map-…`), the elements inside
are located relative to that instance, and `initOfficeMaps()` records each root
in a `WeakSet` so a second call cannot wire the same component twice.

---

## 5. Migration and configuration changes

### 5.1 Migration

`database/migrations/2026_10_18_000001_add_coordinates_to_offices_table.php`

- adds nullable `latitude` (`decimal(10,7)`) and `longitude` (`decimal(10,7)`)
  to `offices`;
- back-fills nothing;
- `down()` drops both columns.

`decimal(10,7)` holds every valid coordinate (at most three digits before the
point) to roughly centimetre precision.

### 5.2 Model

`app/Models/Office.php`: `latitude` and `longitude` added to `$fillable` and
cast to `float`, plus `hasCoordinates()` — the same complete-pair-and-range rule
the mapper uses.

### 5.3 Admin

`app/Filament/Resources/Offices/OfficeResource.php` gains a **Map location**
section with `latitude` (numeric, −90…90) and `longitude` (numeric, −180…180),
both optional. Their helper text states that an office appears on the map only
when both are filled in and that values must be checked against the real
building before being entered.

### 5.4 Configuration

`config/site.php` → `maps`:

| Key | Default | Purpose |
|-----|---------|---------|
| `maps.enabled` | `env('OFFICE_MAP_ENABLED', true)` | master switch |
| `maps.tiles.url` | `https://tile.openstreetmap.org/{z}/{x}/{y}.png` | tile source |
| `maps.tiles.attribution` | OpenStreetMap credit | tile layer attribution |
| `maps.tiles.min_zoom` / `max_zoom` | `2` / `19` | zoom range |

### 5.5 Dependency

`package.json` adds `leaflet` `^1.9.4`. It is imported only by
`resources/js/office-map-render.js`, which Vite emits as its own chunk, fetched
on demand.

---

## 6. Privacy and Content-Security-Policy

`app/Http/Middleware/SecurityHeaders.php` derives the tile origin from
`config('site.maps.tiles.url')` and adds that single origin to `img-src`:

```
img-src 'self' data: https://www.google-analytics.com https://www.googletagmanager.com https://tile.openstreetmap.org
```

- **No wildcard sources** are added (no `*.tile.openstreetmap.org`).
- **No `unsafe-eval`**, no new inline script, no new inline style.
- Leaflet is bundled by Vite and served from this origin, so `script-src` and
  `style-src` need **no** new origin and no CDN is required; the renderer's
  marker images are Vite assets, covered by `'self'`.
- Tiles are requested only after the visitor presses the button, so the tile
  provider is never contacted on a page view nobody opted into.
- Setting `OFFICE_MAP_ENABLED=false` removes the origin from the CSP again.

### Tile provider and attribution

Tiles come from the **OpenStreetMap** standard tile service. Its usage policy is
built for modest traffic, not for unlimited production serving, so:

- the tile URL is a single configuration value;
- if traffic grows, point `OFFICE_MAP_TILE_URL` at a commercial or self-hosted
  tile server **and** add that host to the `img-src` list in `SecurityHeaders`;
- the `© OpenStreetMap contributors` attribution is set on the tile layer and is
  never suppressed.

---

## 7. Enabling the map after verification

1. A member of staff opens **admin → Settings → Offices**.
2. For each office whose location has been checked, they enter **Latitude** and
   **Longitude** (WGS 84, decimal degrees) and save.
3. The map appears on the homepage and the contact page as soon as at least one
   office has both values.

No code change, no migration, no rebuild is required to enable it — only
verified data. Offices left blank keep their normal address card and simply do
not appear as markers. `OFFICE_MAP_ENABLED=false` disables the feature entirely.

---

## 8. Test and build results

### Automated tests added

- `tests/Unit/OfficeMapCoordinatesTest.php` — validity rule, marker filtering,
  directions/provider links, enable/disable rule, tile configuration, model
  helper.
- `tests/Feature/OfficeMapPageTest.php` — both pages render; the map is absent
  when no office has a pair, when only one coordinate is set, when a coordinate
  is out of range, and when the feature is switched off; only verified offices
  become markers; two instances get unique ids; the payload is a data block;
  the CSP carries the tile host with no wildcard and drops it when disabled.

**Not executed: this session's shell failed on every invocation** (`exit 66`,
no output, foreground and background), so `php artisan test`, `npm install
leaflet` and `npm run build` could not be run. They must be run in a working
shell:

```
npm install            # resolves the new leaflet dependency
npm run build          # emits the Leaflet chunk + on-demand CSS
php artisan migrate
php artisan test
```

### Browser verification actually performed (live server, 127.0.0.1:8000)

Checked with a real browser against the running site:

- `/` and `/contact` both return **200**; each still renders its **4 office
  cards**. The homepage's 11 `[data-reveal]` nodes and the desktop sticky header
  are unaffected.
- **`[data-office-map]` count is 0 and "Load interactive map" is absent** on both
  pages — correct, because no office has a verified pair — and **no request to
  `tile.openstreetmap.org` or any Leaflet asset** is made.
- The live `Content-Security-Policy` on `/` contains
  `img-src … https://tile.openstreetmap.org`, with **no wildcard** and **no CDN
  origin** (Leaflet is Vite-bundled). This confirms the middleware change is
  active.
- The component itself was rendered with a synthetic verified office through a
  **temporary preview route that has since been deleted** (the URL now 404s).
  With coordinates present it correctly produced: the labelled `<section>`, a
  real `<button type="button">` named "Load interactive map" described by the
  privacy note, the `role="status"` region, the hidden `role="region"` canvas,
  and a payload containing **exactly one marker** — the office without
  coordinates was excluded — with its keyless directions link, OpenStreetMap
  permalink and the configured tile source. No Leaflet markup is emitted by the
  server.
- Geometry of the new stylesheet rules, measured in the browser (the exact rule
  block, since the built stylesheet is stale until a build runs): frame
  **420px** tall at 1440px wide and **280px** at 375px wide, the canvas overlays
  the frame **exactly** at both sizes, and there is **no horizontal overflow**.

### Still unverified

These need `npm install` + `npm run build` before they can be exercised:

- that the production bundle fetches no Leaflet chunk, stylesheet or tile
  **before** the button is pressed (Vite emits the on-demand chunk, but the built
  output has not been inspected);
- that the map initialises, fits the marker bounds and renders popups **after**
  activation, and that the failure/fallback path works;
- end-to-end keyboard activation and focus visibility.

The current `public/build/manifest.json` does not include the new chunk, so the
map cannot appear on a running server until `npm run build` succeeds.


---

## 9. Outstanding decisions and actions

1. **Supply verified coordinates** for the four offices (or confirm that some
   have closed / moved). Until then the map stays disabled by design.
2. **Revoke/rotate the exposed legacy Google Maps key** in Google Cloud Console
   and review its usage — owned by the key owner, not by this repository.
3. Confirm the tile provider is acceptable for the expected traffic, or choose a
   commercial/self-hosted tile server (one config value plus one CSP origin).
4. Confirm the exact spot (entrance vs. building centroid) for any multi-block
   site.
