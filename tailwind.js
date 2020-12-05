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
                '6-3/4': 'repeat(6, calc(75% - 0.75rem))',
                '6-1/2': 'repeat(6, calc(50% - 0.75rem))',
                '6-1/3': 'repeat(6, calc(33% - 0.75rem))',
                '6-1/4': 'repeat(6, calc(25% - 0.75rem))',
                '6-1/5': 'repeat(6, calc(20% - 0.75rem))',
                '6-1/6': 'repeat(6, calc(16% - 0.75rem))',

                '4-1/1': 'repeat(4, calc(100% - 0.75rem))',
                '4-3/4': 'repeat(4, calc(75% - 0.75rem))',
                '4-1/2': 'repeat(4, calc(50% - 0.75rem))',
                '4-1/3': 'repeat(4, calc(33% - 0.75rem))',
                '4-1/4': 'repeat(4, calc(25% - 0.75rem))',
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
