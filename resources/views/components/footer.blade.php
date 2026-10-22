<footer class="bg-gray-100 border-t border-gray-200 text-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="md:col-span-1">
                <div class="font-luxury text-2xl mb-4" style="letter-spacing: .3em">{{ config('app.brand') }}</div>
                <p class="text-gray-500 text-sm leading-relaxed max-w-xs">
                    A marketplace for fine watches. Every listing is reviewed before it goes live.
                </p>
            </div>

            <div>
                <h3 class="eyebrow mb-5">Shop</h3>
                <ul class="space-y-3 text-sm text-gray-500">
                    <li><a href="{{ route('products.index') }}" class="hover:text-luxury-gold transition-colors">All watches</a></li>
                    <li><a href="{{ route('categories.index') }}" class="hover:text-luxury-gold transition-colors">Brands</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-luxury-gold transition-colors">About us</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-luxury-gold transition-colors">Services</a></li>
                </ul>
            </div>

            <div>
                <h3 class="eyebrow mb-5">Account</h3>
                <ul class="space-y-3 text-sm text-gray-500">
                    <li><a href="{{ route('login') }}" class="hover:text-luxury-gold transition-colors">Sign in</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-luxury-gold transition-colors">Create an account</a></li>
                    <li><a href="{{ route('orders.index') }}" class="hover:text-luxury-gold transition-colors">Orders</a></li>
                </ul>
            </div>

            <div>
                <h3 class="eyebrow mb-5">Stay updated</h3>
                <form class="flex" onsubmit="event.preventDefault(); this.querySelector('button').textContent = 'Thank you';">
                    <label for="footer-email" class="sr-only">Email address</label>
                    <input id="footer-email" type="email" placeholder="Your email address" required
                        class="input-luxury text-sm" style="border-right: 0">
                    <button type="submit" class="btn-luxury" style="padding: 0 1.2rem">Join</button>
                </form>
            </div>
        </div>

        <div class="border-t border-gray-200 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} {{ config('app.brand') }}. All rights reserved.</p>
            <p>Secure payments with Stripe.</p>
        </div>
    </div>
</footer>
