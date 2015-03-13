<?php
$menulist =
    [
        [
            'title' => 'Om',
            'url'   => route('about')
        ], [
            'title' => 'Deltagarlista',
            'url'   => route('participants')
        ], [
            'title' => 'Autentisera dig som deltagare',
            'url'   => route('auth')
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