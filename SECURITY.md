# Security

## Reporting a problem

Please do not open a public issue for a security problem. Use the **Report a vulnerability** button on the Security tab of this project on GitHub. Say what you found and how to repeat it.

## What this project does to protect people

1. Passwords are hashed by Laravel, and two-factor sign-in is available through Jetstream.
2. Website routes for admins and sellers are guarded by role, and API tokens carry abilities that depend on the role.
3. The Google sign-in route for the phone app checks the token with Google. The email comes from the checked token, and an existing account keeps its role and password. The route is limited to ten calls a minute.
4. `/api/image-proxy` refuses paths that contain `..` or start with `/`.
5. Accounts can be switched off with `is_active`, and a switched-off account is signed out.

## What to keep in mind

1. This is a portfolio project. Read the [known issues](README.md#known-issues) before putting it on the internet.
2. The sample accounts use the password `password`. Never run `php artisan db:seed` on a public server.
3. `GET /api/products` returns pending and rejected products too.
4. Payment is confirmed when the customer returns to the site. There is no Stripe webhook.
5. Keep `.env`, Stripe keys and Firebase keys out of the repository, screenshots and bug reports.
