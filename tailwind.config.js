module.exports = {
  mode: 'jit',
  prefix: 'xp-',
  important: '.block_xp',
  purge: [
    './renderer.php',
    './templates/**/*.mustache',
    './classes/form/**/*.php',
    './classes/local/controller/**/*.php',
    './classes/local/shortcode/handler.php',
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
