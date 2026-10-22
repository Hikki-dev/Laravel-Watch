# Contributing

Thank you for helping. This page explains how to make a change that is easy to accept.

## What we ask of every change

1. Never put a password, a key or a real person's details in the code, the docs, a test or a screenshot. Use `.env` for secrets, and never commit it.
2. Keep the three roles apart. Anything an admin or a seller can do must be guarded on the website by `role:` middleware and in the API by a token ability.
3. Anything a person reads, on a page or in the docs, should be in plain English with short sentences.
4. Do not seed the sample accounts anywhere public.
5. Add or update a test when you change behaviour.

## Where things are

1. `app/Http/Controllers` has the website controllers, and `app/Http/Controllers/Api` has the API ones.
2. `app/Livewire` has the live parts of the pages, and `resources/views` has the Blade views.
3. `routes/web.php` and `routes/api.php` list every route.
4. `docs` has the written help. Start with [How it works](docs/How%20it%20works.md).

## Running it and the checks

```bash
composer install
npm install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite && php artisan migrate --seed
npm run build
php artisan test
```

The same build checks run on every pull request: the packages install, the assets build and the routes load. **The test suite has known failures** (see [Fixing problems](docs/Fixing%20problems.md#the-test-suite-has-failures)). A change that makes it better is very welcome. A change that adds new failures is not.

## Sending a change

1. Make a branch.
2. Keep the change small and about one thing.
3. Run `php artisan test` and say how the result compares with before.
4. Open a pull request. Say what changed, why, and how you tested it.

## Reporting a problem

Open an issue and choose the right form. Say what you expected and what happened. Never paste your `.env`, passwords or keys.
