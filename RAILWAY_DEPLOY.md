# Deploying KS Tech Store to Railway

Your Laravel project is **ready to deploy** on [Railway](https://railway.com). Follow this guide.

---

## Prerequisites

- Code pushed to **GitHub** (or GitLab/Bitbucket)
- A [Railway](https://railway.com) account
- GitHub connected to Railway

---

## Step 1: Create a New Project on Railway

1. Go to [railway.com](https://railway.com) and sign in
2. Click **New Project**
3. Select **Deploy from GitHub repo**
4. Choose your `tech_store_app` repository

---

## Step 2: Add a Database (CRITICAL)

Railway runs your app and database in **separate containers**. The app cannot use `127.0.0.1`—you must add a database and connect it.

1. In your project, click **+ New**
2. Select **Database** → **Add MySQL** (or **Add PostgreSQL**)
3. Wait for the database to provision

### Link the database to your app

4. Click your **Laravel/web service** (not the database)
5. Go to **Variables** tab
6. Click **+ New Variable** → **Add Reference**
7. Select your **MySQL** (or PostgreSQL) service
8. Choose **`MYSQL_URL`** (or `DATABASE_URL` for PostgreSQL)
9. Name the variable **`DATABASE_URL`** and save

Laravel will read `DATABASE_URL` and configure the connection. Without this, the app uses `127.0.0.1` (localhost) and will fail with "Connection refused".

**If you use PostgreSQL:** set `DB_CONNECTION=pgsql` and reference `DATABASE_URL` from the Postgres service.

---

## Step 3: Set Environment Variables

In your **Laravel service** → **Variables** tab, add:

| Variable | Value | Required |
|----------|-------|----------|
| `APP_KEY` | Run `php artisan key:generate --show` locally and paste the result | ✅ Yes |
| `APP_ENV` | `production` | ✅ Yes |
| `APP_DEBUG` | `false` | ✅ Yes |
| `APP_URL` | Your Railway URL, e.g. `https://your-app.up.railway.app` | ✅ Yes |
| `DB_CONNECTION` | `mysql` or `pgsql` (match your database) | ✅ Yes |
| `DATABASE_URL` | Reference from MySQL/Postgres service (see Step 2) | ✅ Yes |

**If `DATABASE_URL` doesn't work via reference**, set these manually from your MySQL service’s Variables:
- `DB_HOST` (e.g. `monorail.proxy.rlwy.net`)
- `DB_PORT` (usually `3306`)
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

Other optional variables (keep defaults if unsure):
- `CACHE_DRIVER=file`
- `SESSION_DRIVER=file`
- `FILESYSTEM_DISK=local`
- `QUEUE_CONNECTION=database` — **Recommended** for order confirmation emails (prevents 30s timeout when placing orders)

---

## Step 4: Configure Root Directory (if needed)

If your Laravel app is in a subfolder, set **Root Directory** in Railway service settings to that folder (e.g. `/` if the repo root is the app).

---

## Step 5: Deploy

1. Railway will auto-deploy on every push to your main branch
2. Or click **Deploy** manually
3. Check the **Deployments** tab for build logs

---

## Important: File Uploads (Avatars & Product Images)

Railway uses an **ephemeral filesystem**. Uploaded files (avatars, product images) will be **lost on redeploy**.

### Option A: Use Railway Volumes (Recommended for simplicity)

1. In your project, click **+ New** → **Volume**
2. Create a volume and name it e.g. `uploads`
3. In your **Laravel service** settings, attach the volume
4. Set the **Mount Path** to: `/home/kashoo/Documents/IS/Ecommerce/project/tech_store_app/public/upload`

**Note:** The app root on Railway is typically the workspace root. Set the Volume mount path to: `public/upload` (relative) or the full path shown in Railway's volume settings.

This persists the `public/upload` directory across deploys.

### Option B: Use Cloud Storage (S3, etc.)

For production, consider storing uploads on S3, Cloudflare R2, or similar. This requires code changes to use Laravel's filesystem with an S3 driver.

---

## Step 6: Queue Worker (Required for Order Emails)

Order confirmation emails are queued to avoid timeouts. You **must** run a queue worker for emails to be sent.

### Option A: Add a Worker Service (Recommended)

1. In your Railway project, click **+ New** → **Empty Service**
2. Connect it to the same GitHub repo as your web app
3. Set the **Root Directory** (if any) to match your web service
4. Go to **Settings** → **Deploy**
5. Set **Custom Start Command** to: `php artisan queue:work --sleep=3 --tries=3`
6. Add the same **Variables** as your web service (especially `DATABASE_URL`, `APP_KEY`, `MAIL_*`)
7. Deploy the worker

### Option B: Single Service with Process Manager

If you prefer one service, use a `Procfile` at the project root:

```
web: php -S 0.0.0.0:$PORT -t public
worker: php artisan queue:work --sleep=3 --tries=3
```

Then configure Railway to run both (check Railway docs for multi-process support).

**Important:** Set `QUEUE_CONNECTION=database` on both web and worker services.

---

## Step 7: Create an Admin User

After the first deploy:

1. Open Railway's **Shell** for your service (or use `railway run`)
2. Run: `php artisan tinker`
3. In tinker:
   ```php
   $u = App\Models\User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('your-password'),'is_admin'=>true]);
   ```
4. Or use a seeder if you have one

---

## Files Already Updated for Railway

These files were updated for deployment:

| File | Change |
|------|--------|
| `.nixpacks.toml` | PHP 8.3, Node.js, build steps, migration on start |
| `app/Http/Middleware/TrustProxies.php` | Trust all proxies (for Railway's load balancer) |

---

## Step 8: Run Migrations (include jobs table)

Ensure the `jobs` table exists for the queue:

```bash
php artisan migrate --force
```

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| **"Connection refused" / Host: 127.0.0.1** | You have no database linked. Add MySQL (Step 2), then **Add Reference** → MySQL service → `MYSQL_URL` as `DATABASE_URL` on your web service. Redeploy. |
| Build fails on `composer install` | Ensure `APP_KEY` is set before first deploy |
| 500 error after deploy | Check logs in Railway dashboard; often `APP_KEY` or database config |
| Database connection failed | Verify `DATABASE_URL` or `DB_*` variables; check DB is running; ensure the reference is on the **web** service, not the DB service |
| Uploads disappear on redeploy | Add a Volume (see Option A above) or use S3 |
| Mixed content (HTTP/HTTPS) | Set `APP_URL` to `https://your-app.up.railway.app` |
| **Place Order times out (30s)** | Set `QUEUE_CONNECTION=database` and add a queue worker service (see Step 6) |
| **502 Bad Gateway** after placing order | Do not use `Mail::send()` for order emails — use `Mail::queue()` only. Ensure `QUEUE_CONNECTION=database` and a queue worker is running so the web request returns quickly. |
| Order emails not received | Ensure queue worker is running; check `jobs` table has migrations run |

---

## Useful Commands (Railway Shell)

```bash
php artisan migrate --force   # Run migrations
php artisan db:seed           # Run seeders
php artisan config:clear      # Clear config cache
php artisan cache:clear       # Clear application cache
```

---

## Custom Domain (Optional)

1. In your service → **Settings** → **Domains**
2. Add your custom domain
3. Update `APP_URL` to match
