# Changelog

All notable changes to this project are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Added
- Production Docker stack: multi-stage `Dockerfile` (Node 20 Vite build +
  PHP 8.2-Apache runtime), `compose.yml` with `app` (MySQL 8) services,
  host bind mounts under `/opt/app-data/catetin-prod`, and app healthcheck
  against Laravel's `/up` route.
- Local development overlay `compose.dev.yml` with source bind mounts, a
  `vite` service for HMR, dev-only PHP tuning (`.docker/php/dev.ini`),
  and lightweight `file`/`sync` drivers via `.env.dev.example`.
- Traefik dynamic file `traefik/catetin-prod.yml` (DNS-challenge wildcard TLS,
  shared security-headers and error-pages middlewares).
- Apache vhost, production/dev PHP ini files, and entrypoint script that waits
  for MySQL, links storage, migrates, and caches config only in production.
- `.dockerignore` to keep secrets and build noise out of image layers.
- TrustProxies (`at: '*'`) in `bootstrap/app.php` so URLs generate as `https`
  behind the TLS-terminating reverse proxy.
- Vite dev-server HMR block (`server.host`, `strictPort`, `VITE_HMR_HOST`).
- Rewritten `README.md` (project description, badges, run instructions).
- GitHub Actions CI (`.github/workflows/ci.yml`): Pest tests on SQLite,
  Pint style check, Vite build, compose/traefik validation, image build smoke
  test. No CD — deployment stays manual.
- This changelog.
- `OwnerSeeder` (env-driven `SEED_OWNER_*`, idempotent, 12-char minimum,
  skips cleanly when unset) and `DemoSeeder` gate: `DatabaseSeeder` loads
  demo stalls/menus/sales only with explicit `SEED_DEMO=true`, so production
  seeding can never inject fake data by accident. The previously hardcoded
  demo owner credential is gone from `DatabaseSeeder`.

### Changed
- Compose file renamed to the canonical `compose.yml`.

### Fixed
- Single source of truth for DB credentials (`DB_APP_USER` + `DB_PASSWORD`;
  compose sets both the MySQL user and the app login from them). The previous
  `DB_USERNAME_NONROOT`/`DB_PASSWORD_NONROOT` pair could silently disagree
  with the app side (MySQL creates users only at first init), causing 1045
  access-denied restart loops.
- Entrypoint now verifies app credentials with a real query and exits with an
  actionable message instead of looping on 1045 (`mysqladmin ping` only proves
  liveness, not authentication).
- Corrected Blade component class casing (`SidebarItem`, `PlusButton`) that
  worked on case-insensitive filesystems but fatally broke autoloading (and
  `view:cache`) on Linux.
- Enabled the Apache `remoteip` module required by the vhost's
  `RemoteIPHeader` directive; added `--skip-ssl` to entrypoint MySQL client
  checks (server uses a self-signed cert; traffic stays in-Compose-network).

## [0.1.0] - 2026-08-20
- Initial project: Laravel 12 + Breeze authentication, owner (dashboard, user
  verification, business/menu/category/stock/reports) and pegawai (POS
  transactions, cart, stock updates, history) modules.
