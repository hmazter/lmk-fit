<?php
/** @var \Illuminate\Routing\Router $router */


/*
 * HomeController
 */
$router->get('/', 'HomeController@index');
$router->get('/auth', 'HomeController@auth');
$router->get('/code', 'HomeController@code');
$router->get('/about', 'HomeController@about');

/*
 * ParticipantController
 */
$router->get('/participants', 'ParticipantController@index');
$router->get('/participant/reload/{id}/{timespan?}', 'ParticipantController@reload');


//dd($router);