module.exports = {
  mode: 'jit',
  prefix: 'xp-',
  important: '.block_xp',
  purge: [
    './templates/**/*.mustache',
    './classes/form/**/*.php',
    './classes/local/controller/**/*.php',
    './ui/src/**/*.{js,ts,tsx}',
  ],
  darkMode: false,
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
};
