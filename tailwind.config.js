const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  theme: {
    screens: {
      xs: '320px',
      ...defaultTheme.screens
    },
    extend: {
      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
      },
      keyframes: {
        wave: {
          '0%, 60%, 100%': { transform: 'rotate(0deg)' },
          '10%, 30%': { transform: 'rotate(14deg)' },
          '20%': { transform: 'rotate(-8deg)' },
          '40%': { transform: 'rotate(-4deg)' },
          '50%': { transform: 'rotate(10deg)' },
        }
      },
      animation: {
        wave: 'wave 2.5s infinite'
      }
    },
  },
  purge: {
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
    options: {
      // defaultExtractor: (content) => content.match(/[\w-/.:]+(?<!:)/g) || [],
      whitelistPatterns: [/-active$/, /-enter$/, /-leave-to$/, /show$/],
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/ui'),
  ]
}
