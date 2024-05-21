const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                cream: '#FFF9F5',
                butter: '#FDAE4B',
                midnight: '#0F033A',
                evening: '#251A4D',
                merino: '#F2E7DD',
                hurricane: '#807575',
                dolphin: '#6C6489',
                'burnt-dolphin': '#454059',
                'peach-orange': '#FFC497',
                'seashell-peach': '#FFF0E8',
                'dawn-pink': '#F1E5E4',
                salmon: '#F89377',
                'fair-pink': '#FFEAE4',
            },
        },
    },
    variants: {
        extend: {
            backgroundColor: ['active'],
        }
    },
    content: [
        './app/**/*.php',
        './resources/**/*.html',
        './resources/**/*.js',
        './resources/**/*.jsx',
        './resources/**/*.ts',
        './resources/**/*.tsx',
        './resources/**/*.php',
        './resources/**/*.vue',
        './resources/**/*.twig',
    ],
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
