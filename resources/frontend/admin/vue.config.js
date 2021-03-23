module.exports = {
  devServer: {
    disableHostCheck: true,
  },

  // output built static files to Laravel's public dir.
  outputDir: '../../../public/assets/admin',

  publicPath: process.env.NODE_ENV === 'production'
    ? '/assets/admin/'
    : '',
  // modify the location of the generated HTML file.
  // make sure to do this only in production.
  indexPath: process.env.NODE_ENV === 'production'
    ? '../../../resources/views/admin.blade.php'
    : 'index.html',

  transpileDependencies: ['vuetify'],

  pluginOptions: {
    i18n: {
      locale: 'en',
      fallbackLocale: 'en',
      localeDir: 'locales',
      enableInSFC: false,
    },
  },
}
