# How everything fits

![Browser and phone to Vercel and Laravel, then Neon and Stripe](images/how-it-fits.svg)

This page follows one watch from a shelf to a customer's wrist, so you can see which part does what.

## 1. A seller lists a watch

A seller signs in and fills in a form: brand, model, price, photos and details. The watch is saved with the status **pending**. Nobody can see it yet.

The photos are stored in the database itself. That sounds odd, but it means the store needs no file storage, which is exactly what a serverless host like Vercel wants.

## 2. An admin approves it

The admin opens **Approvals**, looks at the pending watches and brands, and approves or rejects each one. Only approved, active watches appear in the shop. That is why the home page says what you see is what you can buy.

## 3. A visitor finds it

The collection page lists approved watches. Search is a Livewire box: it asks the server after every pause in typing and shows matches under the box. Brand and price filters narrow the list.

On the watch page the visitor can look at photos, and if the watch has them, turn it with a drag. The brand logo and the details come from the same record.

## 4. A customer buys it

The customer adds the watch to the cart and presses checkout. Laravel creates an order and sends the customer to Stripe Checkout, where the card is entered. The card never touches this store. Tax of 8% is added at checkout.

An order can hold watches from several sellers, so each line is its own order item with its own status.

## 5. The seller ships it

The seller sees the order items for their own watches only, and moves them from pending to accepted to shipped. Stripe Connect pays the seller once the sale is complete.

## 6. The same data feeds the app

Everything above is also available as a JSON API for the [Watch-final](https://github.com/Hikki-dev/Watch-final) Flutter app. A token decides what each person may do, so a customer's token cannot delete a watch. See the [API reference](API.md).

## Why it is built this way

* **Server rendered pages** load fast and need little JavaScript. Alpine and Livewire add only the small interactive parts.
* **Roles in one column** (`customer`, `seller`, `admin`) keep the rules easy to read. See [Roles and areas](Roles%20and%20areas.md).
* **One database** holds everything, including images, so moving the store means moving one thing.
