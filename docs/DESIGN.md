# Design system

The storefront and dashboards share one visual language with the Flutter app.

## Themes

Light is the default. A sun/moon button in the navigation switches to dark, and the choice is stored in `localStorage` (`lux-theme`) and applied before first paint by `resources/views/partials/head.blade.php`.

Colours are CSS variables defined in `resources/css/app.css` (`--c-bg`, `--c-panel`, `--c-line`, `--c-text`, `--c-mute`, `--c-accent`, and so on) as space-separated RGB. `tailwind.config.js` maps the Tailwind names `white`, `gray-*`, `indigo-*`, `yellow-600/700` and `luxury-*` onto them, so every existing utility class such as `bg-white` or `text-gray-600` follows the active theme with no per-page changes.

## Type

Self-hosted through `@fontsource` (no external font requests): Cormorant Garamond for headings (`font-luxury`, applied to `h1`/`h2` by default), Hanken Grotesk for the interface, DM Mono for prices (`font-mono`).

## Components

- `.btn-luxury`, `.btn-luxury-outline`, `.card-luxury`, `.input-luxury`, `.eyebrow`, `.pill`, `.stage`, `.reveal` (staggered entrance), `.marquee`; all respect `prefers-reduced-motion`.
- `<x-theme-toggle />` the colour switch.
- `<x-brand-logo :name="$brand" />` shows the logo from `public/images/brands/<file>.webp` on a white plate, falling back to the brand name. Logos cover Patek Philippe, Audemars Piguet, Rolex, Omega, Richard Mille, TAG Heuer, Swatch, Seiko, Casio and Citizen.
- `<x-spin360 :frames="$urls" />` the 360 viewer.

The wordmark comes from `config('app.brand')` (`APP_BRAND`, default `LUXWATCH`).

## 360 viewer

`resources/js/spin360.js` turns any element marked `data-spin360 data-live` into a drag-to-rotate viewer with momentum, keyboard arrows, optional auto-spin and Front / Angle / Side / Back buttons (`data-s360-view`).

The product page shows it when `products.spin_frames` (nullable JSON, an ordered list of frame URLs through one full turn) holds at least 8 entries, with a Photos / 360° switch. Otherwise it shows the photo gallery only. Shoot 36 to 120 frames of the watch on a plain background from a fixed camera, host them anywhere, and store the URLs in `spin_frames`.

## Assets

Brand logos were cleaned (the supplied Audemars Piguet file had a baked-in checkerboard), cropped and converted to WebP.
