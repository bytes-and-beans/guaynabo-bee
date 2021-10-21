const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  mode: 'jit',
  purge: [
      './templates/**/*.{twig,html,js}',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      maxWidth: {
        '1/4': '25%',
        '1/2': '50%',
        '3/4': '75%',
      },
      colors: {
        guaynabo: {
          "gray": '#929496',
          blue: {
            DEFAULT: '#275B72',
            dark: '#212B36',
            start: '#5fc4be',
            end: '#275b72',
          },
          purple: {
            DEFAULT: '#eb0072',
            start: '#eb0072',
            end: '#862367',
          },
          green: {
            DEFAULT: '#8dc54d',
            start: '#8dc54d',
            end: '#016d3b',
          },
          orange: {
            DEFAULT: '#fdc265',
            start: '#fdc265',
            end: '#ee5bd2',
          },
        },
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}

