# The 360 viewer

![A row of photos, a list of links, a script, a drag, a frame](images/viewer-360.svg)

Luxury watch sites let you drag a watch round with your finger. They do it with a row of photos, not a 3D engine, and so does this store.

## How it works

1. A watch is photographed turning once, 60 to 130 times, from a fixed camera.
2. The links to those photos are saved in `products.spin_frames` as a list.
3. `resources/js/spin360.js` shows the first one. When you drag, it works out how far you moved and swaps in the matching photo.
4. Letting go keeps a little momentum. The arrow keys also turn it, and the Front, Angle, Side and Case back buttons jump to a view.

Because it only swaps pictures, it runs smoothly on a phone and needs no model file.

## Loading without slowing the page

The viewer does not download every frame at once. It shows frame 1, waits until it is on screen, then loads the rest one after another while the browser is idle. A watch with 65 frames still lets the page load at once.

Reduced motion is respected: with that setting on, nothing spins by itself.

## Adding a turn to a watch

1. Put the photos in a folder under `public/images/spin/`, for example `public/images/spin/my-watch/f01.webp`, `f02.webp` and so on. 900 pixel wide WebP keeps them small.
2. Save the list on the watch, in the seeder or in the database:

```php
'spin_frames' => array_map(fn ($i) => sprintf('/images/spin/my-watch/f%02d.webp', $i), range(1, 60)),
```

3. Open the watch. A **360°** tab appears next to **Photos**. It needs at least 8 frames.

## The frames in the demo

The Rolex Daytona (65 frames) and the Swatch Big Bold Chrono and Sistem51 (60 frames each) use the brands' own renders. Those belong to Rolex and Swatch, so they are **not in this repository**. The seeder checks whether the files exist. If they do not, the watch simply shows photos and nothing breaks.

Use only images you have the right to show. For your own watches, shoot your own frames.

## Files

| File | What it does |
|---|---|
| `resources/js/spin360.js` | The viewer: drag, momentum, keys, views, lazy loading |
| `resources/views/components/spin360.blade.php` | The markup, `<x-spin360 :frames="$urls" />` |
| `resources/views/products/show.blade.php` | The 360° and Photos tabs |
| `resources/css/app.css` | The `.s360` styles |
