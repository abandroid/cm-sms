let Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('src/Bundle/CmSmsBundle/Resources/public/build/')
    .setPublicPath('/bundles/endroidcmsms/build')
    .setManifestKeyPrefix('/build')
    .cleanupOutputBeforeBuild()
    .addEntry('index', './src/Bundle/CmSmsBundle/Resources/public/src/js/index.js')
    .autoProvidejQuery()
    .enableReactPreset()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();