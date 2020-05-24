let mix = require('laravel-mix');

mix.js('source/app.js', 'public/scripts')
  .sass('source/app.scss', 'public/styles')
  .sourceMaps()
  .setPublicPath('public');