let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/payment';
const source = './platform/plugins/payment';

mix
    .js(source + '/resources/assets/js/payment.js', dist + '/js/payment.js')
    .js(source + '/resources/assets/js/payment-methods.js', dist + '/js/payment-methods.js')

    .sass(source + '/resources/assets/sass/payment.scss', dist + '/css')
    .sass(source + '/resources/assets/sass/payment-methods.scss', dist + '/css')

    .copy(dist + '/js', source + '/public/js')
    .copy(dist + '/css', source + '/public/css');
