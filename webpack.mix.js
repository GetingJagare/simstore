let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.ProvidePlugin({
                $: 'jquery',
                jQuery: 'jquery',
                'window.jQuery': 'jquery',
            })
        ]
    }
});


 mix.sass('resources/assets/sass/app.scss', 'public/css')
 .sass('resources/assets/sass/vendor.scss', 'public/css');

mix.js('resources/assets/js/admin.js', 'public/js');
mix.js('resources/assets/js/app.js', 'public/js');
