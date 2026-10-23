# Getting started

This guide takes you from nothing to a running store on your own computer, with sample watches in it. It takes about ten minutes.

## What you need

* PHP 8.2 or newer and [Composer](https://getcomposer.org)
* Node 20 or newer
* Nothing else. The first run uses a SQLite file, so there is no database to install.

## Run it

```bash
git clone https://github.com/Hikki-dev/Laravel-Watch.git
cd Laravel-Watch
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm run build
php artisan serve
```

Open <http://localhost:8000>. You should see the home page and a row of brand logos.

`npm run build` makes the CSS and JavaScript once. While you change the design, run `npm run dev` in a second terminal instead, and the page reloads by itself.

## Sign in

The seeder makes three accounts so you can see each side of the store:

| Account | Email | What it shows |
|---|---|---|
| Customer | `customer@example.com` | Browsing, cart, checkout, orders |
| Seller | `seller@example.com` | Listing watches, managing orders |
| Admin | `admin@example.com` | Approvals, users, every order |

The password is `password`, unless you set `SEED_PASSWORD` in `.env` before seeding. **Never seed a public site without setting it.** See [Fixing problems](Fixing%20problems.md) if sign in does not work.

## What you will see

The seeder adds 17 watches from Rolex, Omega, Patek Philippe, Audemars Piguet, Richard Mille and Swatch. They come with the photos that are in this repository.

The newest Rolex, Audemars Piguet and Swatch photos, and the 360 turns, belong to those brands, so they are not in the repository. If you have them in `public/images/spin/` and `public/images/products/`, the seeder uses them and three watches become turnable. Without them everything still works with photos only. [The 360 viewer](The%20360%20viewer.md) explains how.

## Check that it works

```bash
php artisan test
```

All the tests should pass. They cover sign in, roles, the cart, products, brands, the API and security.

## Where to go next

* Take the tour in the [README](../README.md#a-tour-of-the-store)
* Understand how the parts connect: [How everything fits](How%20everything%20fits.md)
* Put it online: [Vercel and Neon](Vercel%20and%20Neon.md)
