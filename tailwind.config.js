/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/**/*.php',
    ],

    theme: {
        extend: {
            colors: {
                // 🎨 DIGANOVA Brand — dipakai inline di blade
                nova: {
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    900: '#1e3a8a',
                },
                crystal: {
                    400: '#22d3ee',
                    500: '#06b6d4',
                    600: '#0891b2',
                },
                violet: {
                    500: '#8b5cf6',
                    600: '#7c3aed',
                    700: '#6d28d9',
                },
            },
        },
    },

    plugins: [],
};