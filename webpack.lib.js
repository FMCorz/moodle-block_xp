const HEADER = '/* eslint-disable */\n/* Do not edit directly, refer to ui/ folder. */\n\n';

// Plugin to remove eslint-disable comments except the one we add at the top
class RemoveEslintDisablePlugin {
    constructor(options) {
        this.test = options.test;
    }

    apply(compiler) {
        compiler.hooks.emit.tapAsync('RemoveEslintDisablePlugin', (compilation, cb) => {
            Object.keys(compilation.assets).forEach(name => {
                if (!this.test.test(name)) {
                    return;
                }

                let source = compilation.assets[name].source();
                if (!source.startsWith(HEADER)) {
                    return;
                }

                const rest = source.substring(HEADER.length)
                    .replace(/\/\* *eslint-(?:disable|enable)[\s\S]*?\*\//g, '')
                    .replace(/\/\/ *eslint-(?:disable|enable).*$/gm, '');

                source = HEADER + rest;
                compilation.assets[name] = { source: () => source, size: () => source.length };
            });
            cb();
        });
    }
}

module.exports = {
    HEADER: HEADER,
    RemoveEslintDisablePlugin: RemoveEslintDisablePlugin,
};

