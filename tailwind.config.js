import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import daisyui from 'daisyui'; 


/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
         './resources/js/**/*.js', 
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

     plugins: [forms, typography, daisyui],
    daisyui: {
        themes: ['cupcake', 'dark'], 
        darkTheme: 'dark',
    },
    safelist: [
    'bg-green-700',
    'border-green-900',
    'bg-red-700',
    'border-red-900',
    'bg-blue-700',
    'border-blue-900',
    'bg-amber-600',
    'border-amber-800',
    'bg-gray-700',
    'border-gray-900',
    'text-white',
  ],
};
