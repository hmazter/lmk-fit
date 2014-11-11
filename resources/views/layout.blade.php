<?php
$menulist = [
    [
        'title' => 'Om',
        'url'   => action('LMK\Http\Controllers\HomeController@about')
    ],
    [
        'title' => 'Deltagarlista',
        'url'   => action('LMK\Http\Controllers\ParticipantController@index')
    ],
    [
        'title' => 'Autentisera dig som deltagare',
        'url'   => action('LMK\Http\Controllers\HomeController@getAdd')
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="{{ asset('favicon.ico') }}">

        <title>LMK Fitness</title>

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    </head>

    <body>

        <div class="container">

            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/">LMK Fitness</a>
                    </div>

                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            @foreach($menulist as $menu)
                            <li class="{{ Request::url() == $menu['url'] ? 'active' : '' }}"><a href="{{ $menu['url'] }}">{{ $menu['title'] }}</a></li>
                            @endforeach
                        </ul>
                    </div><!--/.nav-collapse --
                </div><!--/.container-fluid -->
            </nav>

            @yield('content')

            <div class="footer">
                <p>Kristoffer Högberg 2014 | <a href="https://github.com/hmazter/lmk-fit">GitHub</a></p>
            </div>

        </div> <!-- /container -->

        <script src="{{ asset('/js/jquery-2.1.1.min.js') }}"></script>
        <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
    </body>
</html>
