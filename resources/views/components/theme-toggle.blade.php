<button type="button"
    x-data="{ dark: document.documentElement.dataset.theme === 'dark' }"
    @click="dark = ! dark; document.documentElement.dataset.theme = dark ? 'dark' : 'light'; try { localStorage.setItem('lux-theme', dark ? 'dark' : 'light') } catch (e) {}"
    aria-label="Switch colour mode"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center w-11 h-11 rounded-full border border-gray-200 bg-white text-gray-800 transition-transform duration-300 hover:rotate-12 focus:outline-none focus-visible:ring-2 focus-visible:ring-luxury-gold']) }}>
    <svg x-show="dark" x-cloak class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
        <circle cx="12" cy="12" r="4" /><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6L7 7M17 17l1.4 1.4M5.6 18.4L7 17M17 7l1.4-1.4" />
    </svg>
    <svg x-show="! dark" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M20 14.5A8 8 0 0 1 9.5 4 8 8 0 1 0 20 14.5z" />
    </svg>
</button>
