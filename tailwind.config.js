import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

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
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                luxury: ["Playfair Display", "serif"],
            },
            colors: {
                luxury: {
                    gold: "#D4AF37",
                    darkGold: "#B8941F",
                    charcoal: "#2D3748",
                },
            },
        },
    },

    plugins: [forms, typography],
};
