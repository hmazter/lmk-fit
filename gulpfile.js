var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.less("app.less");

    mix.styles(
        ['bootstrap.min.css', 'app.css'],
        'public/css/build.css',
        'public/css'
    );

    mix.version("css/build.css");
});
