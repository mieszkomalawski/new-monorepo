var Encore = require('@symfony/webpack-encore');

Encore
    .autoProvidejQuery()
.setOutputPath('web/build')
.setPublicPath('/build')
.cleanupOutputBeforeBuild()
.addEntry('app', './assets/js/main.js')
.addStyleEntry('global', './assets/css/global.scss')
.enableSassLoader()
.autoProvidejQuery()
.addEntry('item_list', './assets/js/backlog/item_list.js')
.addEntry('item_edit', './assets/js/backlog/item_edit.js')
.enableSourceMaps(!Encore.isProduction());


module.exports = Encore.getWebpackConfig();