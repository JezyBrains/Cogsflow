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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Zenith - Neo-Minimalist Light System
                zenith: {
                    50: '#f9fafb',   // App Background
                    100: '#f3f4f6',  // Secondary BG
                    200: '#e5e7eb',  // Borders
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#3b82f6',  // Primary Accent (Zenith Blue)
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e293b',  // Text Primary
                    900: '#0f172a',  // Dark Accents
                },
            },
            borderRadius: {
                'zenith': '24px',
            },
            boxShadow: {
                'zenith-sm': '0 2px 8px -2px rgba(0, 0, 0, 0.05)',
                'zenith-md': '0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03)',
                'zenith-lg': '0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02)',
                'zenith-xl': '0 30px 40px -10px rgba(0, 0, 0, 0.1)',
            },
        },
    },
    plugins: [],
};
