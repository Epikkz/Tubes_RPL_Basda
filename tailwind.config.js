import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                'retro-dark': '#212121',
                'retro-cream': '#f0eed3',
                'retro-orange': '#de6d3a',
                'retro-blue': '#3b5bd9',
                'retro-red': '#d93939',
            },
            fontFamily: {
                // Ganti dengan font pilihanmu, misalnya Anton atau Bebas Neue
                comic: ['"Bebas Neue"', ...defaultTheme.fontFamily.sans], 
            },
            boxShadow: {
                // Shadow solid tanpa blur untuk efek Neo-Brutalisme
                'brutal': '4px 4px 0px 0px rgba(0,0,0,1)', 
                'brutal-sm': '2px 2px 0px 0px rgba(0,0,0,1)',
            }
        },
    },
    plugins: [],
};