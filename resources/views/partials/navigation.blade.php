<?php
$menulist =
    [
        [
            'title' => 'Om',
            'url'   => action('HomeController@about')
        ], [
            'title' => 'Deltagarlista',
            'url'   => action('ParticipantController@index')
        ], [
            'title' => 'Autentisera dig som deltagare',
            'url'   => action('HomeController@auth')
        ],
    ];
?>
<div id="navbar" class="navbar-collapse collapse">
    <ul class="nav navbar-nav navbar-right">
        @foreach($menulist as $menu)
            <li class="{{ Request::url() == $menu['url'] ? 'active' : '' }}"><a href="{{ $menu['url'] }}">{{ $menu['title'] }}</a></li>
        @endforeach
    </ul>
</div><!--/.nav-collapse -->