# Changes

What changed in each version, newest first.

## 1.0.2 (24 October 2026)

* **Fixed:** the package cache from a developer machine is no longer uploaded to Vercel, where it listed a development only package and stopped the app from starting
* **Security:** the Vercel runtime moves from PHP 8.3.8 to PHP 8.5.12, which includes the October 2026 PHP security fixes
* **New:** a check on every push that boots the app with production packages, built assets and Vercel's settings
* **Better:** the guide for fixing problems explains the two causes of an empty error page on Vercel

## 1.0.1 (24 October 2026)

A sharper, more complete write up.

* **New:** a page about the Flutter shopper app, and a short "two repos, one store" section in the README
* **New:** an MIT licence for the code, and a social preview picture
* **Better:** the screenshots of the public pages are retaken from the live demo at twice the sharpness
* **Better:** the test count in the README is now the real one

## 1.0.0 (23 October 2026)

The store goes online, and the repository is written up as a guided tour.

* **New:** a live demo on Vercel with a Neon Postgres database
* **New:** real 360 views, loaded only when they are on screen
* **New:** the Rolex, Audemars Piguet and Swatch range uses current models
* **New:** photos sit uncropped on a blurred backdrop, so any photo background fits
* **New:** a Checks workflow runs the tests and builds the assets on every push
* **New:** a README tour with screenshots, diagrams and guides in `docs/`
* **Better:** the same code runs on SQLite, MySQL and Postgres, and search ignores capital letters
* **Better:** the brand logo looks the same on every page
* **Better:** the seeded accounts take their password from `SEED_PASSWORD`
* **Fixed:** search could list watches that were switched off
* **Fixed:** uploaded images could not be read back on Postgres

## 0.3.0 (22 October 2026)

A new look.

* **New:** a light and dark theme with one set of colours that every page follows
* **New:** self hosted fonts, a brand logo row and staggered page entrances
* **New:** a first drag to rotate viewer for watches with frames
* **New:** a free hosting setup (Render and TiDB) and a REST API reference
* **Fixed:** Google sign in from the app now checks the Firebase token on the server

## 0.2.0 (January and February 2026)

Sellers, brands and safety.

* **New:** sellers can add brands, and an admin approves them
* **New:** a revamped customer dashboard and admin user management
* **New:** security tests, and accounts that an admin can switch off
* **Fixed:** large image data no longer crashes responses

## 0.1.0 (November 2025)

The first working store: Laravel 12 with Jetstream, Livewire and Sanctum, products, a cart, Stripe checkout, orders and reviews, and an API for the Flutter app.
