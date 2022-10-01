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
        extend: {
            flex: {
                '2': '2 2 0%',
            },
            fontSize: {
                xxs: '0.6875rem' // 11px.
            }
        },
    },
    corePlugins: {
        // Older versions of Moodle do not understand rgb(... / opacity).
        backgroundOpacity: false,
        borderOpacity: false,
        ringOpacity: false,
        textOpacity: false,
        // Removes the @base.
        preflight: false,
        // Space breaks compatibility with older Moodles.
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
