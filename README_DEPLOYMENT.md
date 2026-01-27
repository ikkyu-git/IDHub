# Deployment checklist for IDHub

This checklist describes steps and configuration needed to run IDHub in production.

## 1) Environment variables
- Set `APP_ENV=production` and `APP_DEBUG=false`.
- Ensure `APP_KEY` is set.
- Configure database: `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
- Configure mail: `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`.
- Configure Sentry (optional): `SENTRY_DSN`, `SENTRY_TRACES_SAMPLE_RATE`.
- Configure docs basic fallback (optional): `DOCS_USER`, `DOCS_PASS`.
- Set `QUEUE_CONNECTION` (recommended `database` or `redis`).

## 2) Prepare the server
- Install PHP (8.2+), extensions: mbstring, openssl, pdo, pdo_mysql, redis (optional), zip, bcmath.
- Install Composer and run `composer install --no-dev --optimize-autoloader`.
- Build frontend assets: `npm ci && npm run build` or `pnpm` equivalent.

## 3) Database & Migrations
- Run migrations: `php artisan migrate --force`.
- If using database queue: `php artisan queue:table` (if not present) and `php artisan migrate --force`.
- Seed essential data if needed.

## 4) Caching & config
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`

## 5) Queue workers
- If using `database` or `redis` queue, run Supervisor with config similar to `deploy/supervisor.conf`.
- Example Supervisor commands:
  - `sudo cp deploy/supervisor.conf /etc/supervisor/conf.d/idhub.conf`
  - `sudo supervisorctl reread && sudo supervisorctl update && sudo supervisorctl start idhub-worker:*`

## 6) Scheduler
- Ensure cron runs `php /path/to/artisan schedule:run` every minute, or use Supervisor to run `schedule:work`.

## 7) Logging & Monitoring
- Configure Sentry DSN and set `SENTRY_TRACES_SAMPLE_RATE` (e.g., 0.1).
- Configure log shipping or use `json` log channel to forward logs to ELK/Fluentd.

## 8) Security
- Serve the app over HTTPS (TLS). Use Let's Encrypt or managed certs.
- Run vulnerability scans and dependency checks.
- Restrict access to `/api/docs` and `/metrics` (middleware already present).

## 9) Backups & Recovery
- Set up DB backups and retention policy.
- Back up storage (`storage/app/public`) and keys.

## 10) Release process
- Use blue/green or rolling deployments for zero-downtime.
- Run migrations in a controlled window; prefer non-blocking migrations.

## 11) Rollout & monitoring
- Deploy to a staging environment and run smoke tests.
- After production deploy, monitor Sentry, logs, and health endpoints (`/health/ready`).
