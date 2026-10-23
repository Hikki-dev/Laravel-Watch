# Questions and answers

**Is this a real shop?**
No. It is a portfolio project that shows how a luxury watch marketplace can be built: roles, approvals, a cart, Stripe checkout, an API and a careful design. Nothing is sold.

**Can I try it without installing anything?**
Yes. The live demo is at <https://luxwatch-livid.vercel.app>. Register as a customer to look around. The seller and admin accounts are private, but the [screenshots in the README](../README.md#a-tour-of-the-store) show them.

**Why are there no 3D models?**
Real 3D watch models are expensive and heavy. The best watch sites use a row of photos instead, and so does this store. It is smooth on a phone and needs no model files. See [The 360 viewer](The%20360%20viewer.md).

**Why are the newest Rolex, AP and Swatch photos missing from the repository?**
They belong to those brands. They were used for a private showcase and are kept out of git so the public repository does not republish them. The seeder falls back to older bundled photos.

**Do payments work?**
Checkout uses Stripe in **test mode** when you give it a test key. No real money moves. Without a key, checkout cannot complete, but browsing, search, sign in and the cart all work.

**Why does it store images in the database?**
So it needs no file storage, which suits serverless hosting. It is fine for a prototype. For a big store, use object storage.

**Which database should I use?**
SQLite to look around, MySQL or Postgres online. The code works on all three.

**How do I change the colours or fonts?**
The theme lives in CSS variables in `resources/css/app.css` and is mapped to Tailwind in `tailwind.config.js`. See [Design](DESIGN.md).

**Does it have a dark mode?**
Yes, the moon button in the top bar. Your choice is remembered.

**How do I add a brand logo?**
Put a WebP in `public/images/brands/` and add its name to the map in `resources/views/components/brand-logo.blade.php`. Brands added by an admin with an uploaded image show that image instead.
