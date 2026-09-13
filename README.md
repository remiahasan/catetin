# Catetin

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-6-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Traefik](https://img.shields.io/badge/Traefik-reverse_proxy-24A1C1?style=for-the-badge&logo=traefikproxy&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)
[![CI](https://github.com/remiahasan/catetin/actions/workflows/ci.yml/badge.svg)](https://github.com/remiahasan/catetin/actions/workflows/ci.yml)

**Catetin** is a point-of-sale (POS) and inventory management web app for culinary businesses (cafes, food stalls, small restaurants). Owners manage multiple businesses, menus, categories, stock, users, and revenue reports from an admin dashboard; cashiers (*pegawai*) run daily sales through a cart-and-checkout POS interface with stock deduction and transaction history. The UI is in Indonesian.

> Original source: <https://github.com/remiahasan/catetin.git>

## Roles & Features

| Role | Area | What it does |
|---|---|---|
| Owner | Dashboard | Revenue charts (weekly/monthly), business overview |
| Owner | Verify Users | Approve / reject / edit / delete user accounts |
| Owner | Kelola Bisnis | CRUD for each business/outlet |
| Owner | Menu & Kategori | Menu items and categories per business |
| Owner | Stock & Laporan | Stock levels, stock history/additions, sales reports per business and per employee |
| Pegawai | Transaksi (POS) | Browse menus by category, cart, quantity editing, checkout |
| Pegawai | Update Stok | Reduce stock on use (e.g. ingredients consumed) |
| Pegawai | Riwayat Transaksi | Personal sales history |
| All | Profile | Profile edit, photo upload |

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12, PHP 8.2 |
| Auth | Laravel Breeze + Sanctum |
| Database | MySQL 8 (`pbl_sem_4` by default) |
| Frontend | Blade, Tailwind CSS 3, Alpine.js, Flowbite |
| Charts / UX | ApexCharts, SweetAlert2, Flowbite Datepicker |
| Build | Vite 6 (`npm run dev` / `npm run build`) |
| Prod runtime | Docker (`php:8.2-apache` + MySQL 8), Traefik reverse proxy |

## Requirements

- PHP ^8.2 with `pdo_mysql, bcmath, intl, zip, gd, exif, pcntl, opcache`
- Composer 2, Node 20 + npm
- MySQL 8 (or Docker, which provides it)
- Docker 24+ with Compose v2 (for containerized runs)

## Run It

### A. Classic local setup (no Docker)

```bash
cp .env.example .env
composer install
php artisan key:generate
# point DB_* in .env to your MySQL, then:
php artisan migrate
npm install
npm run build        # or: npm run dev  (for Vite HMR)
php artisan serve    # http://localhost:8000
```

### B. Local development with Docker (hot reloading)

Containers for PHP/Apache, MySQL, and the Vite dev server. PHP/Blade edits apply on the next request; CSS/JS hot-reloads in the browser.

```bash
cp .env.dev.example .env.dev
# fill APP_KEY + DB_PASSWORD in .env.dev
# (generate a key without local PHP: docker compose -f compose.yml -f compose.dev.yml --env-file .env.dev run --rm app php artisan key:generate --show)
docker compose -f compose.yml -f compose.dev.yml --env-file .env.dev up -d --build
```

- App: <http://localhost:8082> · Vite HMR: `ws://localhost:5173`
- `APP_PORT` / `VITE_HMR_HOST` in `.env.dev` adjust the ports/host if needed.

### C. Production with Docker + Traefik

The app container serves plain HTTP; TLS terminates at Traefik on a separate reverse-proxy host, which forwards over the private network.

**On the service server** (project checkout, e.g. `/opt/apps/catetin-prod`):

```bash
cp .env.docker.example .env
# fill APP_KEY + DB_PASSWORD in .env
mkdir -p /opt/app-data/catetin-prod/{mysql,storage,backups}
docker compose up -d --build
```

Key `.env` values: `APP_URL=https://your-domain.com`, `APP_PORT=8082`, `DATA_ROOT=/opt/app-data/catetin-prod`. MySQL is not published — it stays inside the Compose network. Restrict `APP_PORT` to the private network at the host firewall.

**On the reverse-proxy server** (Traefik with file provider + DNS-challenge resolver + shared middlewares): copy `traefik/catetin-prod.yml` to the dynamic-config directory (e.g. `/etc/traefik/dynamic/catetin-prod.yml`) and set the backend URL to the service server:

```yaml
http:
  routers:
    catetin-prod:
      rule: "Host(`your-domain.com`)"
      entryPoints: [websecure]
      service: catetin-prod-backend
      tls:
        certResolver: dns-resolver
        domains: [{ main: "your-domain.com", sans: ["*.your-domain.com"] }]
      middlewares: [security-headers@file, custom-error-pages@file]
  services:
    catetin-prod-backend:
      loadBalancer:
        servers: [{ url: "http://<service-server>:8082" }]
```

Rename `dns-resolver` if your LetsEncrypt resolver is called differently.

## Useful Commands

```bash
php artisan migrate --force          # run inside app container in prod
php artisan storage:link --force     # handled automatically by entrypoint
docker compose exec app php artisan tinker
docker compose logs -f app
```

## Project Layout (highlights)

```
app/Http/Controllers/{Admin,Pegawai,Auth}  # owner / cashier / auth logic
routes/{web,auth}.php                       # role middleware: owner, pegawai
resources/views/{admin,pegawai,auth}        # Blade UI (Indonesian)
database/migrations/                        # business, users, menus, stock, cart, transactions
compose.yml / compose.dev.yml               # prod / local-dev containers
.docker/{apache,php,entrypoint.sh}          # vhost, php.ini, boot script
traefik/catetin-prod.yml                    # Traefik dynamic config (proxy host)
```

## License

MIT — see the [Laravel license](https://opensource.org/licenses/MIT). Application code originates from the repository above.
