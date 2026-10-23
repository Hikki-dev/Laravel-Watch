# Roles and areas

Every account has one role, stored in `users.role`. The role decides which part of the store opens.

| Role | Lands on | Can do |
|---|---|---|
| Customer (default) | Home | Browse, search, favourites, cart, checkout, order history, reviews, profile |
| Seller | Seller dashboard | Everything a customer can, plus list watches and brands, manage their own orders, connect Stripe to be paid |
| Admin | Admin dashboard | Approve or reject watches and brands, manage users, see every order |

## The areas

| Area | Address | Guarded by |
|---|---|---|
| Public store | `/`, `/products`, `/categories` | Nobody |
| Profile, two-factor, API tokens | `/user/...` | Signed in |
| Cart, checkout and orders | `/cart`, `/checkout`, `/orders` | Signed in (the Add to Cart button is shown to customers) |
| Seller area | `/seller/...` | Seller |
| Admin area | `/admin/...` | Admin |
| REST API | `/api/...` | Sanctum token with the right ability |

Web routes are guarded by the `role:` middleware in `app/Http/Middleware/RoleMiddleware.php`. API routes check token abilities instead: `*` for admin, product create, update and delete for sellers, and `order:create` for customers.

An account can also be switched off by an admin. `EnsureUserIsActive` signs out inactive accounts on their next request.

## Things worth knowing

* New watches and brands start as **pending**. Only an admin can make them live.
* Sellers only ever see their own orders and products.
* The tests in `tests/Feature/RoleAccessTest.php` and `ApiTokenPermissionsTest.php` check these rules, so a change that opens something up by accident fails the build.
