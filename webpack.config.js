var Encore = require('@symfony/webpack-encore');

Encore
.setOutputPath('web/build')
.setPublicPath('/build')
.cleanupOutputBeforeBuild()
.addEntry('app', './assets/js/main.js')
.addStyleEntry('global', './assets/css/global.scss')
.enableSassLoader()
.autoProvidejQuery()
.addEntry('backlog', './assets/js/backlog_list.js')
.enableSourceMaps(!Encore.isProduction());


module.exports = Encore.getWebpackConfig();