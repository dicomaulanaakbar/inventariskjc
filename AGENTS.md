# AGENTS.md — Inventaris KJC

## Tech stack
- **Laravel 13** (PHP 8.3+), **Blade + Tailwind 3 + Alpine.js**, **Vite**, **SQLite** (dev)
- Auth: **Laravel Breeze** (no Laravel Boost / AI tooling installed)
- PDF: `barryvdh/laravel-dompdf` — Excel: `maatwebsite/excel`

## Setup & first run
```bash
composer setup
```
Runs: `composer install` → copy `.env` → `key:generate` → `migrate --force` → `npm install --ignore-scripts` → `npm run build`.

`.npmrc` has `ignore-scripts=true` — postinstall hooks won't run.

Seed DB with test users:
```bash
php artisan db:seed
```
Users: `admin@kjc.com` / `password123` (role: admin), `owner@kjc.com` / `password456` (role: owner).

Storage link for image uploads:
```bash
php artisan storage:link
```

## Dev server
```bash
composer dev
```
Runs **4 concurrent processes**: `artisan serve`, `queue:listen`, `pail` (logs), `vite`. All required for full functionality (queue is database-backed, cache/session use database driver).

## Commands
| Command | what |
|---|---|
| `composer test` | `config:clear` then `artisan test` (PHPUnit) |
| `composer dev` | `concurrently` with server + queue + logs + vite |
| `npm run build` | Vite build |
| `npm run dev` | Vite dev server |
| `php artisan pint` | PHP code style (Pint, Laravel's opinionated formatter) |

## Testing
- **In-memory SQLite** (`phpunit.xml` sets `DB_DATABASE=:memory:`) — no external DB needed
- Tests: `tests/Unit` (plain PHPUnit), `tests/Feature` (Laravel, with in-memory SQLite)
- Run focused test: `php artisan test --filter=test_name` or `vendor/bin/phpunit tests/Feature/FooTest.php`

## Architecture
- **Role system**: `users.role` column stores `"admin"` or `"owner"`. Middleware `RoleMiddleware` (aliased as `role` in `bootstrap/app.php`). Duplicate `CheckRole` middleware exists but is unused.
- **Routes**: `/dashboard` is the root redirect target. Profile routes use non-standard `PATCH /profile/update-profile` and `PUT /profile/update-password` alongside `PATCH /profile`.
- **Admin** (`role:admin`) manages CRUD for: kategori, supplier, barang, penjualan, retur, catatan (stok masuk/keluar).
- **Owner** (`role:owner`) sees: laporan stok, keuangan, barang masuk/keluar (PDF + Excel export for keuangan).
- **Admin + Owner** (`role:admin,owner`): read-only access to penjualan list/show and catatan index.
- **Retur routes**: detail route `retur/get-details/{id}` must be defined **before** the resource route (see `routes/web.php:61-68`).

## Models & known quirks
- **Barang**: uses `SoftDeletes`. Has `supplier_id` column (nullable FK, added via migration) but `supplier_id` is NOT in `$fillable` — controller `store`/`update` never save it. Supplier is also excluded from the edit form.
- **BarangJual**: denormalized `total_harga_jual` stored on the record for reports.
- **StockTransaction** model + controller exist but are **dead code** — no route references them, controller references non-existent column `stok_saat_ini`.
- **Owner** model is an empty/unused class (no table, no relationships).
- **`stok/index.blade.php`** view is orphaned — not referenced by any route. References `$barang->deskripsi` and `$barang->harga` which do not match model fields.

## Notable
- `session`, `cache`, and `queue` drivers all default to `database` — the `queue:listen` worker in `composer dev` is needed for async jobs.
- Image uploads stored at `storage/app/public/barang` — `php artisan storage:link` required in production.
- `DashboardController::index()` line 33: `orderBy('desc')` missing explicit column — results are non-deterministic.
- Flash messages use `success`/`error` keys, passed via `with()` (Indonesian labels in views).
