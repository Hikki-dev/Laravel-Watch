import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

// Colours resolve to CSS variables (see resources/css/app.css) so every existing
// utility such as bg-white or text-gray-600 follows the light and dark themes.
const token = (name) => `rgb(var(--c-${name}) / <alpha-value>)`;

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Hanken Grotesk"', ...defaultTheme.fontFamily.sans],
                luxury: ['"Cormorant Garamond"', "Georgia", "serif"],
                mono: ['"DM Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                white: token("panel"),
                gray: {
                    50: token("panel2"),
                    100: token("bg"),
                    200: token("line"),
                    300: token("line2"),
                    400: token("mute2"),
                    500: token("mute"),
                    600: token("mute"),
                    700: token("text2"),
                    800: token("text"),
                    900: token("text"),
                },
                // Jetstream defaults use indigo; route them to the accent so auth, banners
                // and focus rings match the rest of the theme.
                indigo: {
                    50: token("panel2"),
                    100: token("panel2"),
                    200: token("line"),
                    300: token("line2"),
                    400: token("accent"),
                    500: token("accent"),
                    600: token("accent"),
                    700: token("accent-strong"),
                    800: token("accent-strong"),
                    900: token("accent-strong"),
                },
                yellow: {
                    600: token("accent"),
                    700: token("accent-strong"),
                },
                luxury: {
                    gold: token("accent"),
                    darkGold: token("accent-strong"),
                    charcoal: token("text"),
                },
            },
            letterSpacing: {
                eyebrow: "0.22em",
            },
        },
    },

    plugins: [forms, typography],
};
