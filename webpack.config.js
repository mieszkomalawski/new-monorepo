var Encore = require('@symfony/webpack-encore');

Encore
.setOutputPath('web/build')
.setPublicPath('/build')
.cleanupOutputBeforeBuild()
.addEntry('app', './assets/js/main.js')
.addStyleEntry('global', './assets/css/global.scss')
.enableSassLoader()
.autoProvidejQuery()
.addEntry('backlog_list', './assets/js/backlog_list.js')
.addEntry('update_item', './assets/js/update_item.js')
.enableSourceMaps(!Encore.isProduction());


module.exports = Encore.getWebpackConfig();