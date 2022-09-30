const plugin = require('tailwindcss/plugin');

module.exports = {
    prefix: 'xp-',
    important: '.block_xp',
    content: [
        './renderer.php',
        './templates/**/*.mustache',
        './classes/form/**/*.php',
        './classes/local/controller/**/*.php',
        './classes/local/shortcode/handler.php',
        './css/safelist.txt',
        './ui/src/**/*.{js,ts,tsx}',
    ],
    theme: {
        extend: {},
    },
    corePlugins: {
        preflight: false,
        space: false,
    },
    plugins: [
        // Redefine the 'space' plugin because Moodle 3.11 (and older most likely) do not
        // properly parse its generated CSS. This disables the utility `space-[x/y]-reverse`.
        plugin(function({matchUtilities, theme, variants}) {
            matchUtilities(
                {
                    'space-x': (value) => {
                        value = value === '0' ? '0px' : value;
                        return {
                            '& > :not([hidden]) ~ :not([hidden])': {
                                'margin-right': `0`,
                                'margin-left': `${value}`,
                            },
                        };
                    },
                    'space-y': (value) => {
                        value = value === '0' ? '0px' : value;
                        return {
                            '& > :not([hidden]) ~ :not([hidden])': {
                                'margin-top': `${value}`,
                                'margin-bottom': `0`,
                            },
                        };
                    },
                },
                {
                    values: theme('space'),
                    variants: variants('space'),
                    type: 'any',
                }
            );
        }),
    ],
};
