# Fix 502 on Railway (Place Order / Timeout)

502 often happens when **placing an order** because the app sends the confirmation email **in the same request**. If `QUEUE_CONNECTION=sync` (default), the request waits for the email to send and can hit Railway’s timeout.

Do these **3 steps** so the request returns immediately and emails are sent in the background:

---

## 1. Set queue to database

In Railway: **your Laravel service** → **Variables** → add or edit:

```env
QUEUE_CONNECTION=database
```

(Do **not** use `sync` in production on Railway.)

---

## 2. Run migrations (so the `jobs` table exists)

In Railway: open **Shell** for your Laravel service and run:

```bash
php artisan migrate --force
```

Or ensure your deploy runs this (e.g. in your build/deploy config).

---

## 3. Run a queue worker

You need a **second process** that runs the queue. Two options:

### Option A – Second service (recommended)

1. In your Railway project, click **+ New** → **Empty Service**.
2. Connect the **same GitHub repo** and same **Root Directory** as your web app.
3. **Variables**: copy the same variables from your web service (at least `APP_KEY`, `DATABASE_URL`, `MAIL_*`).
4. **Settings** → **Deploy** → set **Custom Start Command** to:
   ```bash
   php artisan queue:work --sleep=3 --tries=3
   ```
5. Deploy. The worker will process queued emails (and any other jobs).

### Option B – Procfile (if your stack supports it)

In the project root, create or edit `Procfile`:

```
web: php -S 0.0.0.0:$PORT -t public
worker: php artisan queue:work --sleep=3 --tries=3
```

Then configure Railway to run both `web` and `worker` (see Railway docs for your stack).

---

After this:

- The **web** request returns right after saving the order (no 502 from timeout).
- The **worker** sends the confirmation email from the queue.

If you still get 502, check:

- Variables are set on the **web** service (especially `QUEUE_CONNECTION=database`).
- Migrations have run (so `jobs` table exists).
- Logs in Railway for the web service and the worker.
