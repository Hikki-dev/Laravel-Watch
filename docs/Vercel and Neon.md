# Vercel and Neon

The live demo runs on Vercel with a Neon Postgres database. Both have free tiers, so it costs nothing to try. This page shows how it was put together and why.

![Vercel, Laravel, Neon and Stripe](images/how-it-fits.svg)

## Why these two

Vercel runs code only when someone asks for a page, so there is no server to look after, and it serves photos and scripts from a fast network. Laravel does not run on Vercel by itself, so a small community runtime, `vercel-php`, does that job. Neon is Postgres that wakes up when needed, which pairs well with that.

## What was changed to make it work

| Problem | What was done |
|---|---|
| Vercel can only write to `/tmp` | `bootstrap/app.php` moves Laravel's storage there when it runs on Vercel |
| No long running database connection | The **pooled** Neon host is used, with SSL required (`DB_SSLMODE=require`) |
| Uploaded images need somewhere to live | They are stored in the database (`bytea` on Postgres), as they already were |
| Postgres treats `LIKE` as case sensitive | Search uses `whereLike`, which works on every database |
| The MySQL only `ALTER TABLE ... AFTER` migration | It now runs only on MySQL and MariaDB |
| Pages made too many database trips | Sessions use cookies and the cache uses `/tmp` files |
| Photos and frames are large | They are served as static files with long cache headers |

## Files that matter

| File | What it does |
|---|---|
| `vercel.json` | Builds the PHP function and the static files, sets cache headers, runs in `iad1` |
| `api/index.php` | The one entry point that hands every request to Laravel |
| `.vercelignore` | Keeps secrets and dev folders out of the upload |

## Put your own copy online

1. **Create the database.** Make a Neon project in the region next to your Vercel functions (for example `us-east-1` and `iad1`). Copy the **pooled** connection details.
2. **Fill it.** From your computer, with the Neon values in the environment:

   ```bash
   SEED_PASSWORD="choose-a-strong-one" \
   DB_CONNECTION=pgsql DB_HOST=... DB_PORT=5432 DB_DATABASE=... \
   DB_USERNAME=... DB_PASSWORD=... DB_SSLMODE=require \
   php artisan migrate --force --seed
   ```

   Set `SEED_PASSWORD`. Without it the admin, seller and customer accounts get the password `password`.
3. **Build the assets.** `npm run build` makes `public/build`. `.vercelignore` replaces `.gitignore` for uploads, so that folder goes up with the rest.
4. **Link and set variables.** Run `vercel link`, then add each variable with `vercel env add NAME production`: `APP_KEY` (from `php artisan key:generate --show`), `APP_URL`, `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` and `DB_SSLMODE`.
5. **Deploy.** `vercel deploy --prod`. If a new project asks you to sign in to open it, turn off Deployment Protection under Settings.

## Not included

* **Stripe and Google sign in** need their keys as environment variables. Browsing, search, sign in and the cart work without them. Checkout does not.
* **Mail** needs a mail service. Without one, password reset and email checks cannot send.
* The brand photos and 360 frames are **not in the repository**. Upload from your own folder, as above, or the watches show the bundled photos.

## The other way: Render and TiDB

A free Docker setup on Render with a TiDB MySQL database is in [Deployment](DEPLOYMENT.md). Use that if you want a classic, always on server.

## Keep the PHP runtime current

`vercel.json` names the PHP runtime (`vercel-php@0.9.0`, PHP 8.5). The runtime decides which PHP your site runs, and PHP had two critical security fixes in October 2026. Do not use `vercel-php@0.7.4`, which ships PHP 8.3.8. See [Security](../SECURITY.md).
