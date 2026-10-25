# Security

## Reporting a problem

Please do not open a public issue for a security problem. Use GitHub's private reporting: **Security, Report a vulnerability** on this repository. Say what you found, how to repeat it and what it could allow. You will get an answer as soon as it can be read.

## What to know

* This is a portfolio project, not a shop that holds real money or cards. Payments use Stripe, and cards are entered on Stripe, never here.
* The seeder creates sample accounts. Their password comes from `SEED_PASSWORD`, and is `password` if you do not set it. **Never seed a public site without setting it.**
* Roles are checked on every route, and API tokens carry abilities. Tests in `tests/Feature` guard this.
* Uploaded images are stored in the database. Images are not scanned.
* Composer reports known advisories for some dependencies. Update them with `composer update` and run the tests before deploying a real site.

## Keys and settings

Real keys belong in the host's environment settings, never in git. See [Configuration](docs/Configuration.md).

## PHP security releases (October 2026)

Two critical PHP flaws, CVE-2026-109618 and CVE-2026-109619 (nicknamed PHP2Shell), let an attacker run code on a server without signing in. They are fixed in PHP 8.5.12, 8.4.27, 8.3.36 and 8.2.35, released on 21 October 2026. A third flaw, CVE-2026-115560, was disclosed on 23 October. It affects only unusually configured applications and had no patch when this was written, so follow the PHP security announcements.

What this means for this project:

* **Vercel.** `vercel.json` uses the `vercel-php@0.8.0` runtime, which ships PHP 8.4.27 (the fixed release). The older `vercel-php@0.7.4` runtime ships PHP 8.3.8, which does not have the fix, and no newer PHP 8.3 build of it exists. Do not go back to it. The `vercel-php@0.9.0` runtime (PHP 8.5.12) is patched too, but its database library (libpq 13) cannot talk to Neon without extra settings, see [Fixing problems](docs/Fixing%20problems.md). The Production boot check workflow fails if the PHP in the selected runtime is older than the fixed release.
* **Docker.** The `Dockerfile` starts from `php:8.2-bookworm`. A fresh build gets the current 8.2 release (8.2.35 when checked on 24 October 2026), so rebuild and redeploy images that were built earlier.
* **Your own server.** Update PHP to a fixed release. Laravel 12 runs on PHP 8.2 to 8.5.
