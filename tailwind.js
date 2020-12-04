const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            gridTemplateColumns: {
                '6-3/4': 'repeat(6, calc(75% - 40px))',
                '6-1/2': 'repeat(6, calc(50% - 40px))',
                '6-1/3': 'repeat(6, calc(33% - 40px))',
                '6-1/4': 'repeat(6, calc(25% - 40px))',
                '6-1/5': 'repeat(6, calc(20% - 40px))',
                '6-1/6': 'repeat(6, calc(16% - 40px))',
            },
        },
    },

    variants: {
        opacity: ['responsive', 'hover', 'focus', 'disabled'],
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/ui')
    ],
};
