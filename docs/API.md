# REST API

Base URL: `https://<your-host>/api`. JSON in and out. Authenticated routes need `Authorization: Bearer <token>` (Laravel Sanctum). The Flutter app [Watch-final](https://github.com/Hikki-dev/Watch-final) is the main client.

## Authentication

| Method | Path | Body | Notes |
|---|---|---|---|
| `POST` | `/register` | `name`, `email`, `password`, ... | Returns a token |
| `POST` | `/login` | `email`, `password` | Returns a token |
| `POST` | `/auth/google` | `id_token`, `name?` | Firebase/Google ID token, verified server-side (needs `FIREBASE_WEB_API_KEY`). The email comes from the verified token and must be verified by Google. Creates a `customer` on first sign-in. An existing account keeps its role and password. Throttled to 10 per minute. 401 for an invalid token, 503 if not configured |
| `POST` | `/logout` | none | Revokes the current token |
| `GET` | `/user` | none | Current user |

A successful auth response looks like:

```json
{ "status": "success",
  "data": { "user": { "id": 1, "role": "customer" },
            "access_token": "...", "token_type": "Bearer",
            "abilities": ["order:create"] } }
```

### Token abilities

| Role | Abilities |
|---|---|
| `admin` | `*` |
| `seller` | `product:create`, `product:update`, `product:delete` |
| `customer` | `order:create` |

## Public

| Method | Path | Purpose |
|---|---|---|
| `GET` | `/products` | List products, wrapped as `{ "data": [...] }` |
| `GET` | `/products/{id}` | Product with images, category, seller and reviews |
| `GET` | `/brands` | Brands |
| `GET` | `/image-proxy?path=...` | Serves a file under `public/` (avoids CORS on web). Rejects `..` and absolute paths |

A product has: `id`, `seller_id`, `category_id`, `name`, `slug`, `description`, `brand`, `model`, `reference_number`, `price`, `original_price`, `stock_quantity`, `condition_type`, `movement_type`, `case_material`, `dial_color`, `strap_material`, `water_resistance`, `year_manufactured`, `warranty_info`, `is_featured`, `is_active`, `status`, `image_url`.

## Authenticated (any role)

| Method | Path | Purpose |
|---|---|---|
| `GET` | `/cart` | Current cart |
| `POST` | `/cart/add/{product}` | Add a product |
| `PATCH` | `/cart/{cart}` | Update a cart line |
| `DELETE` | `/cart/{cart}` | Remove a cart line |
| `PATCH` | `/cart/product/{product}` | Update quantity by product id (mobile) |
| `DELETE` | `/cart/product/{product}` | Remove by product id (mobile) |
| `DELETE` | `/cart/clear` | Empty the cart |
| `POST` | `/checkout` | Create an order from the cart. Body: `shipping_address`, `city`, `postal_code`, `country`. 8% tax is added. 400 if the cart is empty |
| `GET` | `/orders`, `/orders/{id}` | Order history |
| `PUT` | `/profile` | Update name, email, phone, address |
| `POST` | `/profile/photo` | Upload a profile photo (multipart) |
| `GET` | `/favorites` | List favourites |
| `POST` | `/favorites` | Toggle a favourite |
| `DELETE` | `/favorites/{productId}` | Remove a favourite |
| `POST` | `/products/{product}/reviews` | Add a review |
| `DELETE` | `/reviews/{review}` | Delete a review |
| `GET` | `/seller/stripe/connect` | Start Stripe Connect onboarding |

## Seller (abilities `product:create`, `product:update`, `product:delete`)

| Method | Path | Purpose |
|---|---|---|
| `POST` | `/seller/products` | Create a product (starts as pending) |
| `PUT` | `/seller/products/{product}` | Update |
| `DELETE` | `/seller/products/{product}` | Delete |

## Admin (ability `*`)

| Method | Path | Purpose |
|---|---|---|
| `GET` | `/admin/dashboard` | Totals, including paid revenue and pending products |
| `GET` | `/admin/approvals` | Pending products |
| `POST` | `/admin/products/{product}/approve` | Approve |
| `POST` | `/admin/products/{product}/reject` | Reject |
| `POST`, `PUT`, `DELETE` | `/admin/categories`, `/admin/categories/{category}` | Category management |
| `GET` | `/users` | All users, as `{ "data": [...] }` |
| `POST` | `/users` | Create a user: `name`, `email`, `password` (min 6), `role?` |
| `PUT` | `/users/{id}` | Update name, email, role, `is_active`, optional password |
| `DELETE` | `/users/{id}` | Delete a user |

## Errors

Validation failures return `422` with an `errors` object. Missing resources return `404`, missing permissions `403`, and unauthenticated requests `401`.
