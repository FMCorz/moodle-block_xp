const { merge } = require('webpack-merge');
const common = require('./webpack.common.js');
const { RemoveEslintDisablePlugin } = require('./webpack.lib.js');

module.exports = merge(common, {
    mode: 'development',
    devtool: false,
    plugins: [
        new RemoveEslintDisablePlugin({
            test: /-lazy\.js$/
        }),
    ],
});
