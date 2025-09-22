import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#000000',
                    50: '#f8f8f8',
                    100: '#e7e7e7',
                    200: '#d1d1d1',
                    300: '#b0b0b0',
                    400: '#888888',
                    500: '#6d6d6d',
                    600: '#5d5d5d',
                    700: '#4f4f4f',
                    800: '#454545',
                    900: '#3d3d3d',
                    950: '#000000',
                },
                secondary: {
                    DEFAULT: '#FFD700',
                    50: '#fffef7',
                    100: '#fffaeb',
                    200: '#fff3c7',
                    300: '#ffe998',
                    400: '#ffd74f',
                    500: '#ffc726',
                    600: '#ffb31a',
                    700: '#e6940f',
                    800: '#cc7a0d',
                    900: '#a6620a',
                    950: '#613902',
                },
                gold: {
                    DEFAULT: '#FFD700',
                    50: '#fffef7',
                    100: '#fffaeb',
                    200: '#fff3c7',
                    300: '#ffe998',
                    400: '#ffd74f',
                    500: '#ffc726',
                    600: '#ffb31a',
                    700: '#e6940f',
                    800: '#cc7a0d',
                    900: '#a6620a',
                    950: '#613902',
                }
            }
        },
    },
    plugins: [],
};
