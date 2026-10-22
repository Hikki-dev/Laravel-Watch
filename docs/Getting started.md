# Getting started

This page takes you from an empty folder to a working shop on your own computer. It should take about five minutes.

## What you need

* **PHP 8.2 or newer**, with the `pdo_sqlite`, `mbstring`, `xml`, `gd`, `zip` and `curl` extensions. `php -m` lists what you have.
* **[Composer](https://getcomposer.org)**, which installs the PHP packages.
* **Node 20 or newer**, which builds the CSS and JavaScript.

You do not need MySQL to look around. The default setup keeps everything in one SQLite file.

## 1. Get the code and the packages

```bash
git clone https://github.com/Hikki-dev/Laravel-Watch.git
cd Laravel-Watch
composer install
npm install
```

## 2. Make your settings file

```bash
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

`.env` holds your settings, and `key:generate` fills in the secret `APP_KEY`. Never share `.env` or commit it.

## 3. Create the tables and the sample shop

```bash
php artisan migrate --seed
```

This builds the database and adds three accounts, six brands and 17 watches. The accounts are:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@example.com` | `password` |
| Seller | `seller@example.com` | `password` |
| Customer | `customer@example.com` | `password` |

These accounts have a known password. Use them on your own computer only.

## 4. Build the styles and start the server

```bash
npm run build
php artisan serve
```

Open <http://127.0.0.1:8000>. To change the CSS or JavaScript while you work, run `npm run dev` in a second terminal instead of `npm run build`.

## 5. Take the tour

1. Sign in as the **customer**. Open a watch, add it to the cart and open the cart. The tax is added for you.
2. Sign in as the **seller** (use a private window). Add a product. It shows as **pending**.
3. Sign in as the **admin**. Open **Approvals** and approve the product. It now appears in the shop.

## Paying with Stripe (optional)

Checkout sends the customer to a Stripe payment page. To try it:

1. Make a free [Stripe](https://dashboard.stripe.com) account and switch it to **test mode**.
2. Copy the **secret key** (it starts with `sk_test_`) into `.env` as `STRIPE_SECRET=`.
3. Run `php artisan config:clear`.
4. Check out as the customer and pay with the test card `4242 4242 4242 4242`, any future date and any three digits.

Sellers can press **Connect with Stripe** on their dashboard to create a Stripe Express account. That also needs the secret key.

## Using MySQL instead

Create an empty database, then in `.env` change:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_watch
DB_USERNAME=root
DB_PASSWORD=
```

Run `php artisan migrate --seed` again.

## Using the phone app with it

Set `API_BASE_URL` in the [Watch-final](https://github.com/Hikki-dev/Watch-final) `.env` to `http://<your-computer>:8000/api`. If something does not work, start with [Fixing problems](Fixing%20problems.md).
