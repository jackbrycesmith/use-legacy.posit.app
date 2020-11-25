const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

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
      // https://tailwindcss.com/docs/upgrading-to-v2#configure-your-font-size-scale-explicitly
      fontSize: {
        xs: '0.75rem',
        sm: '0.875rem',
        base: '1rem',
        lg: '1.125rem',
        xl: '1.25rem',
        '2xl': '1.5rem',
        '3xl': '1.875rem',
        '4xl': '2.25rem',
        '5xl': '3rem',
        '6xl': '4rem',
      },
      boxShadow: {
          solid: '0 0 0 2px currentColor',
          outline: `0 0 0 3px rgba(156, 163, 175, .5)`,
          'outline-gray': `0 0 0 3px rgba(254, 202, 202, .5)`,
          'outline-blue': `0 0 0 3px rgba(191, 219, 254, .5)`,
          'outline-green': `0 0 0 3px rgba(167, 243, 208, .5)`,
          'outline-yellow': `0 0 0 3px rgba(253, 230, 138, .5)`,
          'outline-red': `0 0 0 3px rgba(254, 202, 202, .5)`,
          'outline-pink': `0 0 0 3px rgba(251, 207, 232, .5)`,
          'outline-purple': `0 0 0 3px rgba(221, 214, 254,, .5)`,
          'outline-indigo': `0 0 0 3px rgba(199, 210, 254, .5)`,
      },
      colors: {
        ...defaultTheme.colors,
        'blue-gray': colors.blueGray,
        'warm-gray': colors.warmGray,
        'light-blue': colors.lightBlue,
        'rose': colors.rose,
        'orange': colors.orange,
        'primary-yellow': {
          50: '#FFFCF4',
          100: '#FFF9EA',
          200: '#FFF1CA',
          300: '#FFE8A9',
          400: '#FFD769',
          500: '#FFC629',
          600: '#E6B225',
          700: '#997719',
          800: '#735912',
          900: '#4D3B0C'
        }
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

  variants: {
    opacity: ['responsive', 'hover', 'focus', 'disabled'],
  },

  purge: {
    content: [
      './storage/framework/views/*.php',
      './app/**/*.php',
      './resources/**/*.html',
      './resources/**/*.js',
      './resources/**/*.jsx',
      './resources/**/*.ts',
      './resources/**/*.tsx',
      './resources/**/*.php',
      './resources/**/*.vue',
    ],
    options: {
      // defaultExtractor: (content) => content.match(/[\w-/.:]+(?<!:)/g) || [],
      whitelistPatterns: [/-active$/, /-enter$/, /-leave-to$/, /show$/],
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ]
}
