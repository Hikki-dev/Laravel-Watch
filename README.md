# Laravel-Watch

A luxury **watch marketplace** built with **Laravel 12**, **MySQL** and **Stripe**. It serves a server-rendered web storefront with customer, seller and admin areas, and a **REST API** that powers the [Watch-final](https://github.com/Hikki-dev/Watch-final) Flutter app.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-database-4479A1?logo=mysql&logoColor=white)
![Stripe](https://img.shields.io/badge/Payments-Stripe-635BFF?logo=stripe&logoColor=white)
![Tailwind](https://img.shields.io/badge/Tailwind-Vite-06B6D4?logo=tailwindcss&logoColor=white)

## Features

**Customers**
- Browse and search watches (Livewire live search), filter by category, read reviews and leave one.
- Cart, **checkout with Stripe Checkout**, and order history. Tax (8%) is added at checkout.
- Favourites, profile editing and profile photo.
- Register and sign in with email/password or **Google** (Socialite). Two-factor authentication and API tokens come with Jetstream.

**Sellers**
- Seller dashboard, product management and order management (Livewire).
- **Stripe Connect** onboarding so sellers can be paid.
- New products and categories start as **pending** until an admin approves them.

**Admins**
- Dashboard stats (revenue, pending approvals), user management, order list and details.
- **Approve or reject** products and categories.

**API for the mobile app**
- Token auth with **Laravel Sanctum**. Google sign-in verifies the Firebase ID token server-side. Abilities depend on role: `*` for admin, product create/update/delete for seller, `order:create` for customer.
- Products, brands, cart, orders, favourites, reviews, profile, checkout, seller product CRUD, admin approvals and user CRUD. See [docs/API.md](docs/API.md).

## Tech stack

| Area | Choice |
|---|---|
| Framework | Laravel 12, PHP 8.2+ |
| Auth | Jetstream (Livewire stack), Fortify, Sanctum, Socialite (Google) |
| UI | Blade, Livewire 3, Alpine.js, Tailwind CSS, Vite |
| Database | MySQL in production (SQLite by default in `.env.example`) |
| Payments | Stripe Checkout and Stripe Connect (`stripe/stripe-php`) |
| Deployment | Docker (`Dockerfile`) and `Procfile`, hosted on Railway |
| Tests | PHPUnit 11 |

## Roles

`users.role` is `customer` (default), `seller` or `admin`. Web routes are guarded by the `role:` middleware, and API routes by Sanctum token abilities.

## Quick start

Requirements: PHP 8.2+, Composer, Node 20+, and MySQL (or SQLite for a quick look).

```bash
git clone https://github.com/Hikki-dev/Laravel-Watch.git
cd Laravel-Watch
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Edit `.env` (database, Stripe, Google, see [Configuration](#configuration)), then:

```bash
php artisan migrate --seed   # creates tables and sample users, categories and watches
npm run dev                  # Vite, in one terminal
php artisan serve            # app on http://localhost:8000, in another
```

The seeder (`database/seeders/LegacyDataSeeder.php`) creates a sample admin, seller and customer plus catalogue data. **These are development accounts with a known password. Do not seed them in production.**

## Configuration

| Variable | Purpose |
|---|---|
| `APP_KEY`, `APP_URL`, `APP_ENV`, `APP_DEBUG` | Core. Set `APP_DEBUG=false` in production |
| `DB_CONNECTION` and `DB_*` (or `DB_URL`, `MYSQL*` on Railway) | Database |
| `STRIPE_SECRET` | Stripe Checkout and Connect (read with `env()`) |
| `CORS_ALLOWED_ORIGINS` | Comma-separated origins allowed to call the API from a browser. Defaults to any origin |
| `FIREBASE_WEB_API_KEY` | Web API key of the Firebase project. Used to verify the ID token on `POST /api/auth/google` |
| `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URL` | Google sign-in on the web |
| `FIREBASE_CREDENTIALS` | Firebase config (`config/firebase.php`) |
| `MAIL_*` | Email verification and password reset |

## Project structure

```
app/
├── Http/Controllers/        # Web controllers (storefront, cart, checkout, admin, seller)
│   └── Api/                 # JSON API controllers used by the mobile app
├── Http/Middleware/         # RoleMiddleware, EnsureUserIsActive
├── Livewire/                # ProductSearch, Admin/*, Seller/OrderManagement
├── Models/                  # User, Product, ProductImage, Category, Cart, Order,
│                            # OrderItem, Review, Favorite
└── Actions/                 # Fortify and Jetstream actions
routes/web.php               # Storefront, admin and seller routes
routes/api.php               # REST API (Sanctum)
database/migrations/         # Schema
database/seeders/            # LegacyDataSeeder (sample data)
resources/views/             # Blade views and Livewire views
tests/Feature/               # Auth, roles, API, cart, products, security tests
Dockerfile, Procfile, docker-entrypoint.sh   # Deployment
```

## Testing

```bash
php artisan test
```

Feature tests cover registration and login, roles and redirects, API auth and token permissions, cart, product and user CRUD, brand approval, and security.

## Deployment

- **Free hosting**: see [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md). `render.yaml` deploys the Docker image to Render's free plan against a free TiDB Cloud (MySQL) database.
- **Docker**: the `Dockerfile` builds PHP 8.2, installs Composer and Node dependencies, builds assets with Vite, and `docker-entrypoint.sh` runs `php artisan migrate --force` and `php artisan optimize` before serving on `$PORT`.
- **Procfile**: `web` clears caches, migrates against the `mysql` connection and runs `php artisan serve`.

## Known issues and security notes

Read these before exposing the app publicly:

1. **Seeded sample accounts** use a known password. Never seed production.
2. `GET /api/products` lists only approved, active products. Admin tokens see everything.
3. **Images are stored in the database** (`image_data` columns) and an `/api/image-proxy` route serves files from `public/`. Fine for a prototype, but consider object storage at scale.

## License

Laravel is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT). No separate license is declared for this project's own code.

## Author

Built by [Yusuf (Hikki-dev)](https://github.com/Hikki-dev).
