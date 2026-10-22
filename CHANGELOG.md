# Changes

## Docs and safety

1. New README with screenshots of every role, two diagrams, a real list of known issues and the true state of the tests.
2. New guides: Getting started, How it works, Deploying, Fixing problems and Questions and answers, next to the API reference.
3. Removed the `/fix-db` route. It ran `migrate:fresh --seed` for anyone who opened the address, which would erase the whole database on a public server.
4. A Checks workflow builds the project on every push.

## Google sign-in for the phone app

1. `POST /api/auth/google` now checks the Firebase ID token with Google instead of trusting the email in the request. The email must be verified by Google, an existing account keeps its role and password, and the route is limited to ten calls a minute.
2. New API reference, `docs/API.md`.

## Brands, images and CORS (January 2026)

1. Sellers can add brands. A new brand waits for an admin to approve it, with its own tab on the Approvals page. Brands get an automatic letter icon when they have no picture.
2. Products can take an image address as well as an uploaded file.
3. `/api/image-proxy` serves pictures with the right CORS headers for the Flutter web build.
4. Favourites work through the API and on MySQL.
5. Admin user management and cart image fixes, the customer dashboard was redone, and more tests were added.

## Google sign-in and cart sync (January 2026)

1. Google sign-in on the website (Socialite) and for the app.
2. The cart is kept on the server, so it is the same on the website and in the app.
3. Stricter Sanctum rules: token abilities depend on the role.

## Pictures in the database (January 2026)

1. Product and profile pictures can be stored in the database.
2. The API controllers for the app and the order status migration were added, and the admin and seller dashboards were updated.

## Stripe (December 2025)

1. Checkout with Stripe Checkout, and Stripe Connect so sellers can be paid.

## First version (November 2025)

1. The storefront, cart, orders, admin and seller areas with roles, built on Laravel and Jetstream.
2. Docker and Railway set-up, with HTTPS and proxy fixes and MySQL support.
