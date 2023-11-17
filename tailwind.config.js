/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        borderColor: theme => ({
            ...theme('colors'),
            DEFAULT: theme('colors.slate.300', 'currentColor'),
            'primary': '#3490dc',
            'secondary': '#ffed4a',
            'danger': '#e3342f',
        }),
        container: {
            maxWidth: {
                'sm': '350px',
            },
            center: true,
            padding: {
                'DEFAULT': '1rem',
                'sm': '2rem',
                'lg': '4rem',
                'xl': '5rem',
                '2xl': '6rem',
            },
        },
        screens: {
            'sm': '300px',
            'md': '800px',
            'lg': '1240px',
            'xl': '1280px',
            '2xl': '1536px',
        },
        extend: {
            colors: {
                'dominant': '#FFF',
                'complement': '#F4F7FE',
                'ascent': '#4318FF',
                'ascent-dark': '#2B3674',
            },
            boxShadow: {
                'lg': '0px 0px 12px #7F71B129',
            }
        },

    },
    plugins: [],
}