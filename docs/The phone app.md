# The phone app

This store has a companion: [Watch-final](https://github.com/Hikki-dev/Watch-final), a Flutter app for shoppers. This page explains how the two fit, in plain words.

## Who does what

* **This repository (Laravel-Watch)** is the marketplace. It owns the database, the rules and the website. It also offers a JSON API.
* **Watch-final** is only a window onto it. It does not have its own database of watches. It asks this API for the catalogue, the cart, the favourites and the profile.

## How a person gets in

1. In the app, the person signs in with email and password or with Google. **Firebase** handles that part.
2. The app sends the Firebase ID token to `POST /api/auth/google`.
3. This store checks the token with Google, finds or creates the account (a new person starts as a `customer`), and answers with an **API token**.
4. The app keeps that token on the phone and sends it with every later request.

The token carries abilities that match the person's role, so a customer's token cannot create or delete a watch. See [Roles and areas](Roles%20and%20areas.md) and the [API reference](API.md).

## What the app asks for

| In the app | Call to this store |
|---|---|
| The catalogue | `GET /api/products` |
| Cart | `GET /api/cart`, `POST /api/cart/add/{id}`, `PATCH` and `DELETE /api/cart/product/{id}`, `DELETE /api/cart/clear` |
| Favourites | `GET`, `POST` and `DELETE` on `/api/favorites` |
| Profile and photo | `PUT /api/profile`, `POST /api/profile/photo` |
| Seller inventory | `/api/seller/products` |

The app's own page, [Watch-final](https://github.com/Hikki-dev/Watch-final), lists every call it makes and how to point it at your own copy of this store with `API_BASE_URL`.

## Pointing the app at your store

The app reads `API_BASE_URL` from its `.env` file. Set it to `https://<your-host>/api`.

**On Vercel the address is different.** Vercel keeps its own `/api` folder for functions, so this store's API answers at `https://<your-host>/api/api`. The live demo's API is `https://luxwatch-livid.vercel.app/api/api`. The app also lists a hard coded fallback address, which is an older host of this store, so set the variable rather than trusting the fallback.

To let the web version of the app call your API, put its address in `CORS_ALLOWED_ORIGINS`. See [Configuration](Configuration.md).

## If the app shows an empty list

The app cannot reach the API, or `API_BASE_URL` is wrong. Open `https://<your-host>/api/products` (or `/api/api/products` on Vercel) in a browser. If you see JSON, the store is fine and the address in the app is the problem.
