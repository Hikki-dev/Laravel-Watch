# Contributing

Fixes and ideas are welcome. This page says how to set up, check your work and send a change.

## Set up

Follow [Getting started](docs/Getting%20started.md). You need PHP 8.2 or newer, Composer and Node 20 or newer.

## Check your work

```bash
php artisan test
npm run build
```

Both must pass. The same two commands run on every pull request through [the checks workflow](.github/workflows/checks.yml).

## What we ask

* **One change per pull request**, with a message that says what and why in plain words.
* **A test** for new behaviour, next to the existing ones in `tests/Feature`.
* **Stay inside the theme.** Use the colour names already in the pages (`bg-white`, `text-gray-600`) so light and dark both work. See [Design](docs/DESIGN.md).
* **Look at it.** Open the page in a browser, on a phone width too, and add screenshots when the look changes.
* **No secrets.** Never commit `.env`, keys or passwords.
* **No pictures you do not own.** Do not add brand photos or frames. See the legal note in the [README](README.md#legal-and-the-pictures).

## Good places to start

* A page that is missing a test.
* A watch profile that needs a better photo or a real 360 turn of your own.
* A sentence in a guide that is not clear. Plain words beat clever ones.

## Questions

Open an [issue](https://github.com/Hikki-dev/Laravel-Watch/issues/new/choose).
