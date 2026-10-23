# How it works (for changing the code)

A map of the project for anyone who wants to change something.

```
app/
  Http/Controllers/        Web pages: shop, cart, checkout, admin, seller
    Api/                   JSON controllers used by the Flutter app
  Http/Middleware/         RoleMiddleware, EnsureUserIsActive
  Livewire/                Live search, admin user list, seller orders
  Models/                  User, Product, ProductImage, Category, Cart, Order,
                           OrderItem, Review, Favorite
  Actions/                 Fortify and Jetstream actions
bootstrap/app.php          Routing, middleware, and /tmp storage on Vercel
config/database.php        MySQL, Postgres and SQLite connections
database/
  migrations/              The schema
  seeders/LegacyDataSeeder Sample users, brands and watches
resources/
  css/app.css              Theme tokens and components
  js/spin360.js            The 360 viewer
  views/                   Blade pages and Livewire views
    components/            brand-logo, watch-photo, spin360, theme-toggle, ...
routes/web.php             Store, admin and seller routes
routes/api.php             REST API
api/index.php              Vercel entry point
vercel.json                Vercel build and routing
tests/Feature/             26 test files
```

## A request, in code

1. The browser asks for `/products/3`.
2. `routes/web.php` sends it to `ProductController@show`.
3. The controller loads the product, its images and a few related watches, and renders `resources/views/products/show.blade.php`.
4. The page uses small components: `<x-brand-logo>` for the logo, `<x-watch-photo>` for the photo, `<x-spin360>` for the turn.
5. Alpine switches between the 360° and Photos tabs without another request.

## Components worth knowing

| Component | Job |
|---|---|
| `x-brand-logo` | Shows the brand logo from `public/images/brands/` on a white plate, or an uploaded image, or just the name |
| `x-watch-photo` | Shows a photo uncropped over a blurred copy of itself, so any photo background fits the square |
| `x-spin360` | Markup for the 360 viewer |
| `x-theme-toggle` | The light and dark switch |

## Database notes

* Images are stored in `image_data` and `profile_photo_data`. On Postgres they are `bytea` and the models turn strings into streams on the way in.
* Search uses `whereLike`, so it is case insensitive on every database.
* Orders hold one item per watch, each with its own status, so a basket can mix sellers.

## Tests

`php artisan test` runs everything against an in memory SQLite database. `.github/workflows/checks.yml` runs the same on every push and pull request, after building the assets.

## Changing things safely

* Run the tests before and after.
* Keep new pages inside the theme: use the existing Tailwind names (`bg-white`, `text-gray-600`) and they follow light and dark by themselves.
* Add a test next to the behaviour you change.
