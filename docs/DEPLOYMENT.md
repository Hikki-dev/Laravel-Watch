# Free hosting

This setup runs the whole project at no cost. Free tiers change, so check each provider's current limits before relying on them.

| Part | Where | Cost | Catch |
|---|---|---|---|
| Laravel API and web storefront | [Render](https://render.com) free web service (Docker) | Free | Sleeps after about 15 minutes idle; the first request then takes around a minute |
| MySQL database | [TiDB Cloud Serverless](https://tidbcloud.com) free tier (MySQL compatible) | Free | Storage is capped, and product images are stored in the database |
| Sign-in | Firebase Authentication (Spark plan) | Free | |
| Payments | Stripe **test mode** | Free | Live payments carry Stripe's per-transaction fees |
| Flutter web app | GitHub Pages (see the Watch-final repo) | Free for public repos | |

## 1. Database (TiDB Cloud Serverless)

1. Create a free Serverless cluster and a database, for example `luxwatch`.
2. Open **Connect**, choose a general connection, and note the host, port (`4000`), user and password.
3. TLS is required. The Docker image already trusts the system certificates, so the blueprint sets `MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt`.

## 2. API (Render)

1. In Render choose **New > Blueprint** and select this repository. It reads `render.yaml`.
2. Fill in the prompted values:
   - `APP_KEY`: run `php artisan key:generate --show` locally and paste the result.
   - `APP_URL`: the Render URL, for example `https://luxwatch-api.onrender.com`.
   - `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: from TiDB.
   - `FIREBASE_WEB_API_KEY`: the Web API key of your Firebase project.
   - `STRIPE_SECRET`: your Stripe **test** secret key.
   - `CORS_ALLOWED_ORIGINS`: the URL of the Flutter web app, for example `https://hikki-dev.github.io`.
3. Deploy. `docker-entrypoint.sh` runs `php artisan migrate --force` on every start.
4. Check `https://<your-service>/up` returns 200.

Do not run the seeder in production. It creates accounts with a known password. For a portfolio demo, seed once, then change those passwords or delete the accounts.

## 3. Keeping it awake (optional)

A free uptime monitor (for example UptimeRobot) that requests `/up` every 10 to 14 minutes avoids the cold start.

## 4. Point the app at it

In the Watch-final repo set the `API_BASE_URL` repository variable to `https://<your-service>/api`. See its `docs/DEPLOYMENT.md`.

## 5. Firebase

Add the Pages domain (for example `hikki-dev.github.io`) under **Authentication > Settings > Authorized domains**.

## Vercel and Neon Postgres

An alternative to Render. `vercel.json` builds `api/index.php` with the community `vercel-php` runtime and serves `public/` statically; `bootstrap/app.php` moves Laravel's writable storage to `/tmp` on Vercel.

1. Create a Neon project (pick the region next to your Vercel functions, for example `us-east-1` with `iad1`) and use its **pooled** host.
2. Run the migrations and seed from your machine with the Neon values in the environment: `DB_CONNECTION=pgsql DB_HOST=... DB_PORT=5432 DB_DATABASE=... DB_USERNAME=... DB_PASSWORD=... DB_SSLMODE=require php artisan migrate --force --seed`. Set `SEED_PASSWORD` to a strong value first, because the seeded admin, seller and customer accounts otherwise use `password`.
3. Build assets with `npm run build` (the `public/build` folder is uploaded because `.vercelignore` replaces `.gitignore`), then link the project and add `APP_KEY`, `APP_URL`, `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` and `DB_SSLMODE` with `vercel env add`.
4. Deploy with `vercel deploy --prod`. Sessions use cookies and the cache uses `/tmp` (see `vercel.json`), so pages need few database round trips.

Notes: uploads are stored in the database (`bytea` on Postgres), which suits serverless. Stripe and Google sign-in need their keys added as environment variables. New Vercel projects have Deployment Protection on; turn it off under Settings to make the site public.
