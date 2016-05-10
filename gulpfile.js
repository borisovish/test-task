var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
    mix.styles(['bootstrap.min.css','AdminLTE.min.css','skins/skin-blue.min.css','magnific-popup.css','app.css']);
    mix.scripts(['jQuery-2.1.4.min.js','bootstrap.js','lte.js','link-submit.js','magnific-popup.js','magnific-popup-triggers.js'])
});
