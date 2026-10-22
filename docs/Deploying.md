# Deploying

The hosted copy of Laravel-Watch runs on [Railway](https://railway.app) with a MySQL database. Anything that runs PHP 8.2 and MySQL will do.

## Before you go live

* Set `APP_ENV=production` and `APP_DEBUG=false`.
* Set a real `APP_KEY` (`php artisan key:generate --show`).
* Point `APP_URL` at your public address. The app forces HTTPS links in production and trusts the proxy in front of it.
* Use a real database, not the SQLite file.
* **Do not run the seeder.** It creates accounts with the password `password`.
* Use Stripe **live** keys only when you are ready to take real money.
* Add the `STRIPE_SECRET`, `FIREBASE_WEB_API_KEY`, `GOOGLE_*` and `MAIL_*` values from the [Configuration table](../README.md#configuration).

## With Docker

The `Dockerfile` builds PHP 8.2, installs Composer and Node packages, builds the assets with Vite and copies everything into the image. When the container starts, `docker-entrypoint.sh` runs `php artisan migrate --force` and `php artisan optimize`, and then starts the server on `$PORT`.

```bash
docker build -t laravel-watch .
docker run --rm -p 8000:8000 -e PORT=8000 --env-file .env laravel-watch
```

The container migrates the database when it starts, so `.env` must point at a database the container can reach, for example a MySQL one (`DB_CONNECTION=mysql` and the `DB_*` values). The default SQLite file is not inside the image.

## On Railway

1. Create a project from this repository. Railway builds the `Dockerfile`.
2. Add a **MySQL** service. Railway fills in the `MYSQL*` variables, and the app reads them (or `DB_URL`).
3. Add the variables above to the web service.
4. Deploy. The first start creates the tables. The shop is empty, so sign up, then give the account the admin role in the database, for example `UPDATE users SET role = 'admin' WHERE email = 'you@example.com';`.

The `Procfile` is the alternative start command for hosts that read one: it clears the caches, migrates the `mysql` connection and runs `php artisan serve`.

## After deploying

* Visit the home page, the product list and `/api/products`.
* Sign in and place a test order with a Stripe test key.
* Check that a seller's new product shows under **Approvals**.
