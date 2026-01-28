<nav x-data="{ open: false }" class="bg-white shadow-lg relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="font-luxury text-2xl font-bold text-gray-800">
                        CHRONOS
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(!Auth::check() || Auth::user()->role === 'customer')
                        <x-nav-link href="{{ url('/') }}" :active="request()->is('/')">
                            {{ __('Home') }}
                        </x-nav-link>
                    @endif

                    @if (!Auth::check() || Auth::user()->role === 'customer')
                        <x-nav-link href="{{ route('categories.index') }}" :active="request()->routeIs('categories.*')">
                            {{ __('Brands') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')">
                            {{ __('Watches') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
                            {{ __('About Us') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('services') }}" :active="request()->routeIs('services')">
                            {{ __('Services') }}
                        </x-nav-link>
                    @endif

                    @auth
                        @if (Auth::user()->role === 'seller')
                            <x-nav-link href="{{ route('seller.dashboard') }}" :active="request()->routeIs('seller.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('seller.products.index') }}" :active="request()->routeIs('seller.products.*')">
                                {{ __('My Products') }}
                            </x-nav-link>
                        @endif
                    @endauth

                    @auth
                        @if (Auth::user()->isAdmin())
                            <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.index')">
                                {{ __('Users') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')">
                                {{ __('Orders') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('admin.products.approvals') }}" :active="request()->routeIs('admin.products.approvals')">
                                {{ __('Approvals') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.index')">
                                {{ __('Products') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Right Side -->
                @auth
                    @if (Auth::user()->role === 'customer')
                        <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-luxury-gold flex items-center mr-4">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m2.6 8L6 7m1 6v4a1 1 0 001 1h8a1 1 0 001-1v-4m-2 0L12 9l-3 4m0 0a2 2 0 102 2 2 0 00-2-2z"></path>
                            </svg>
                            Cart
                        </a>
                    @endif

                    <!-- Settings Dropdown -->
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>Welcome, {{ Auth::user()->name }}</div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                @if(Auth::user()->role !== 'admin')
                                    <x-dropdown-link href="{{ route('orders.index') }}">
                                        {{ __('My Orders') }}
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-gray-200"></div>

                                <!-- Authentication -->
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="btn-luxury-outline text-sm px-4 py-2 border-2 border-luxury-gold text-luxury-gold rounded-lg hover:bg-luxury-gold hover:text-white transition">Sign In</a>
                        <a href="{{ route('register') }}" class="btn-luxury text-sm px-4 py-2 bg-luxury-gold text-white rounded-lg hover:bg-luxury-darkGold transition">Register</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(!Auth::check() || Auth::user()->role === 'customer')
                <x-responsive-nav-link href="{{ url('/') }}" :active="request()->is('/')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
            @endif
             @if (!Auth::check() || Auth::user()->role === 'customer')
                <x-responsive-nav-link href="{{ route('categories.index') }}">
                    {{ __('Brands') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('products.index') }}">
                    {{ __('Watches') }}
                </x-responsive-nav-link>
            @endif
             @auth
                @if (Auth::user()->isAdmin())
                    <x-responsive-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.index')">
                        {{ __('Users') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')">
                        {{ __('Orders') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('admin.products.approvals') }}" :active="request()->routeIs('admin.products.approvals')">
                        {{ __('Approvals') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="flex items-center px-4">
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link href="{{ route('profile.show') }}">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1 px-4">
                    <x-responsive-nav-link href="{{ route('login') }}">
                        {{ __('Sign In') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('register') }}">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
