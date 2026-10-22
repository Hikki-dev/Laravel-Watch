# Questions and answers

**Is this a real shop?**
No. It is a portfolio and learning project, and the watches, prices and photographs are sample content. Nothing is for sale.

**Which sign-in accounts exist?**
Only the three sample accounts, after you run `php artisan migrate --seed`: `admin@example.com`, `seller@example.com` and `customer@example.com`, all with the password `password`.

**Does it need Stripe to run?**
No. Browsing, the cart, sellers and admins all work without it. Only the pay step needs `STRIPE_SECRET`.

**How is a seller paid?**
Sellers connect a Stripe Express account from their dashboard. Right now the checkout charges the shop's own Stripe account for the whole order. Splitting the money between sellers is not done yet.

**Why are brands called categories in the code?**
A brand is stored as a `Category`. The website calls them brands.

**Can I use MySQL, Postgres or SQLite?**
SQLite for a quick look and MySQL in production are the ones the project is set up for. The code uses plain Eloquent, so other databases supported by Laravel should work, but they are not tested.

**What does the phone app need from this?**
Just the REST API. See [API.md](API.md) and the [Watch-final](https://github.com/Hikki-dev/Watch-final) repository.

**Where do the pictures live?**
In the database. See [How it works](How%20it%20works.md#pictures).

**Can I use the code?**
There is no licence file yet, so all rights are reserved for now. Ask first.
