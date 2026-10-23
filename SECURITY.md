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
