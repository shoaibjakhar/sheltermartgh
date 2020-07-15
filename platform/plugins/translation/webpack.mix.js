let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/translation';
const source = './platform/plugins/translation';

mix
    .js(source + '/resources/assets/js/translation.js', dist + '/js')
    .js(source + '/resources/assets/js/locales.js', dist + '/js')
    .js(source + '/resources/assets/js/theme-translations.js', dist + '/js')

    .sass(source + '/resources/assets/sass/translation.scss', dist + '/css')
    .sass(source + '/resources/assets/sass/theme-translations.scss', dist + '/css')

    .copy(dist + '/js', source + '/public/js')
    .copy(dist + '/css', source + '/public/css');
