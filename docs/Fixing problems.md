# Fixing problems

## `php artisan migrate` says it cannot find the database

With SQLite the file must exist. Run `touch database/database.sqlite` (or create an empty file by hand), and check that `.env` says `DB_CONNECTION=sqlite`.

## The page has no styles

The CSS is built by Vite. Run `npm run build` once, or keep `npm run dev` running while you work. If you see "Vite manifest not found", this is why.

## "No application encryption key has been specified"

Run `php artisan key:generate`.

## The page looks wrong or stale after changing `.env`

Run `php artisan config:clear`. Then, if you use routes or views you edited, `php artisan route:clear` and `php artisan view:clear`.

## Paying does nothing, or Stripe shows an error

* Check that `STRIPE_SECRET` is set, and that it is a **test** key (`sk_test_...`) while you are testing.
* Run `php artisan config:clear` after changing it.
* Use the test card `4242 4242 4242 4242`.

## Google sign-in fails

The website needs `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` and `GOOGLE_REDIRECT_URL`, and the redirect address must also be listed in your Google Cloud project. The phone app's `/api/auth/google` route needs `FIREBASE_WEB_API_KEY`. Without it the route answers `503`.

## The phone app cannot reach my computer

* Use your computer's network address, not `127.0.0.1`, and start the server with `php artisan serve --host=0.0.0.0`.
* Allow port 8000 through your firewall.
* For the Flutter web build, check that the address of the web page is allowed in `config/cors.php`.

## A watch I added is missing from the shop

New products from a seller are `pending` until an admin approves them. Sign in as an admin and open **Approvals**.

## A page waits forever in a screenshot or test browser

The admin dashboard refreshes itself every ten seconds with Livewire, so a browser that waits for the network to be quiet never sees it go quiet. Wait for a fixed time instead.

## The test suite has failures

`php artisan test` currently gives **58 passed, 27 failed, 1 skipped**. The tests use an in-memory database, so your data is safe.

| Test class | What fails |
|---|---|
| `RoleAccessTest`, `RoleRedirectTest` | An admin, seller or customer made with a factory and signed in with `actingAs` is sent to `/login` or gets a 403 |
| `BrandApprovalTest` | Brand creation and approval checks |
| `ProductCrudTest` | Seller create, update and delete |
| `UserCrudTest`, `AdminUserManagementTest` | Admin user list, create, update and delete, and profile updates |
| `CartApiTest` | Add, update and remove through the API |
| `EmailVerificationTest`, `PasswordConfirmationTest` | The stock Jetstream screens |

The common thread is a signed-in test user not being recognised, so the first place to look is the guard and session settings the tests run with. The running app, shown in the README screenshots, does not have this problem. A fix would be a very welcome contribution.

## Still stuck?

Open an issue and use the **Problem** template. Say what you ran, what you expected and what happened, and paste the error text. Never paste your `.env`.
