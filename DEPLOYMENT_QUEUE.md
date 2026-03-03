Redis + Laravel Queue Setup

1) Install Redis (Ubuntu/Debian)

sudo apt update
sudo apt install -y redis-server
sudo systemctl enable --now redis-server
sudo systemctl status redis-server

2) PHP Redis driver options

- Preferred: install the `phpredis` extension (faster, native):

sudo apt install -y php-redis
# or via pecl
# pecl install redis

- If `phpredis` is not available, we added `predis/predis` to `composer.json` as a fallback. Run:

composer require predis/predis

3) Environment

In `.env` set (we updated `.env.example`):

QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null
REDIS_QUEUE=default

4) Start a worker (development)

# run from project root
php artisan queue:work redis --sleep=3 --tries=3 --timeout=90 --memory=128

Or use the helper script:

./scripts/queue-worker.sh

5) Supervisor (production)

- Copy `scripts/supervisor-queue.conf` to `/etc/supervisor/conf.d/laravel-queue.conf` and update the `command` path to your project.
- Then:

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-queue:*

6) Testing

- Dispatch a test job (emails/SMS/PDF) and ensure it's handled by the worker. Example tinker:

php artisan tinker
>>> \App\Jobs\SendTestEmail::dispatch('you@example.com');

7) Notes

- Ensure `QUEUE_CONNECTION=redis` in all environments where you want background processing.
- On managed platforms (Railway, Heroku), use their provided Redis addon and point `REDIS_URL` accordingly.
- Keep `--tries` and `--timeout` tuned to your job profile.

8) Tinker examples (dispatch queued test jobs)

Run these from project root (ensure `QUEUE_CONNECTION=redis` and Redis is running):

php artisan tinker
>>> \App\Jobs\SendTestEmail::dispatch('you@example.com', 'Hello from queue', 'This is a queued test email.');
>>> \App\Jobs\SendTestSms::dispatch('+201234567890', 'This is a queued test SMS.');
>>> \App\Jobs\GenerateTestPdf::dispatch('<h1>PDF from queue</h1><p>Generated at ' . now() . '</p>');

After dispatch, run the worker:

./scripts/queue-worker.sh

Check `storage/app/public` for the generated PDF and `storage/logs/payment.log` for job logs.
