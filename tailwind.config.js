const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  mode: 'jit',
  purge: [
      './templates/**/*.{twig,html,js}',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    fontFamily: {
      'sans': ['twcentury', ...defaultTheme.fontFamily.sans],
      'advantage': ['advantage', ...defaultTheme.fontFamily.sans],
      'serif': [...defaultTheme.fontFamily.serif],
      'mono': [...defaultTheme.fontFamily.mono]
    },
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
            start: '#016d3b',
            end: '#8dc54d',
          },
          orange: {
            DEFAULT: '#fdc265',
            start: '#ee4b2d',
            end: '#fdc265',
          },
        },
      },
      outline: {
        darken: '2px solid #00000050',
      },
    },
  },
  variants: {
    extend: {
      backgroundImage: ['hover', 'focus'],
      outline: ['focus'],
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}

