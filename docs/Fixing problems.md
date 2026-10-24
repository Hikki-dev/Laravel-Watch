# Fixing problems

Find what you see, then try the fix. Most problems here are small.

## The page is blank or has no styling

You have not built the assets. Run `npm run build` (or `npm run dev` while working), then reload. The tests say `Vite manifest not found` for the same reason.

## Sign in does nothing, or says the password is wrong

* Use the seeded email and the password `password`, or the value you gave `SEED_PASSWORD` **when you seeded**. Changing the variable later does not change accounts that already exist.
* On a hosted copy, remember that the seeder sets the password once. To change it, update the users in the database.

## "No application encryption key has been specified"

Run `php artisan key:generate`. On Vercel set `APP_KEY` as an environment variable.

## I seeded again but the home page looks the same

The home page caches the brands and featured watches for an hour. Run `php artisan cache:clear` (or delete the files in `storage/framework/cache`).

## There is no 360° tab on a watch

The watch needs at least 8 frames in `spin_frames`, and the image files must exist. The brand frames are not in the repository, so a fresh clone shows photos only. See [The 360 viewer](The%20360%20viewer.md).

## Tabs, search or buttons do nothing

Open the browser console. If `livewire.js` shows `Unexpected token '<'`, something printed text before the script. A PHP warning is the usual cause: fix the warning, or set `APP_DEBUG=false`. Hard reload afterwards, because the browser may have cached the broken script.

## On Vercel: asked to sign in to Vercel

New projects have Deployment Protection on. Turn it off under **Settings, Deployment Protection** for a public site.

## On Vercel: error 500 or "could not connect"

* Check the database variables, and that `DB_SSLMODE` is `require`.
* Use the **pooled** Neon host (it has `-pooler` in the name).
* Look at the function logs in the Vercel dashboard. The first request after a quiet time can be slow while the database wakes up.

## Uploaded photos do not show on Postgres

Make sure you are on the latest code. Postgres hands back binary columns as a stream, and `ImageController` reads it. Older copies print nothing.

## Still stuck

Open an [issue](https://github.com/Hikki-dev/Laravel-Watch/issues/new/choose) with what you did, what you expected and what happened.

## Every page shows an empty error on Vercel, but the API still answers

Two things cause this, and both were reproduced on a clean machine:

* **The assets were not built.** The pages need . Vercel does not run npm <command>

Usage:

npm install        install all the dependencies in your project
npm install <foo>  add the <foo> dependency to your project
npm test           run this project's tests
npm run <foo>      run the script named <foo>
npm <command> -h   quick help on <command>
npm -l             display usage info for all commands
npm help <term>    search for help on <term>
npm help npm       more involved overview

All commands:

    access, adduser, approve-scripts, audit, bugs, cache, ci,
    completion, config, dedupe, deny-scripts, deprecate, diff,
    dist-tag, docs, doctor, edit, exec, explain, explore,
    find-dupes, fund, get, help, help-search, init, install,
    install-ci-test, install-test, link, ll, login, logout, ls,
    org, outdated, owner, pack, ping, pkg, prefix, profile,
    prune, publish, query, rebuild, repo, restart, root, run,
    sbom, search, set, shrinkwrap, stage, star, stars, start,
    stop, team, test, token, trust, undeprecate, uninstall,
    unpublish, unstar, update, version, view, whoami

Specify configs in the ini-formatted file:
    /Users/hikki/.npmrc
or on the command line via: npm <command> --key=value

More configuration info: npm help config
Configuration fields: npm help 7 config

npm@11.16.0 /Users/hikki/.nvm/versions/node/v24.18.0/lib/node_modules/npm for this project, so run  on your own machine before . Without it every web page fails with "Vite manifest not found" while  and  still work.
* **A package cache from your own machine went up with the code.**  and  list development packages such as . On Vercel those packages are not installed, so the app stops with "Class Laravel\Pail\PailServiceProvider not found".  now keeps these files out of the upload.

To see the real error, set  on a preview deployment only, then ask for the page with .
