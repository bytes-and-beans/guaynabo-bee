const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  mode: 'jit',
  purge: [
      './templates/**/*.{twig,html,js}',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        guaynabo: {
          "gray": '#929496',
          blue: {
            DEFAULT: '#275B72',
            dark: '#212B36',
            start: '#275b72',
            end: '#5fc4be',
          },
          purple: {
            DEFAULT: '#eb0072',
            start: '#862367',
            end: '#eb0072',
          },
          green: {
            DEFAULT: '#8dc54d',
            start: '#8dc54d',
            end: '#016d3b',
          },
          orange: {
            DEFAULT: '#fdc265',
            start: '#ee4b2d',
            end: '#fdc265',
          },
        },
      },
    },
  },
  variants: {
    extend: {
      backgroundImage: ['hover', 'focus'],
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}

