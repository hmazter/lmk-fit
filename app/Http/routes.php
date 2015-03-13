<?php
/** @var \Illuminate\Routing\Router $router */


/*
 * HomeController
 */
$router->get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
$router->get('/auth', ['as' => 'auth', 'uses' => 'HomeController@auth']);
$router->get('/code', ['as' => 'code', 'uses' => 'HomeController@code']);
$router->get('/about', ['as' => 'about', 'uses' => 'HomeController@about']);

/*
 * ParticipantController
 */
$router->get('/participants', ['as' => 'participants', 'uses' => 'ParticipantController@index']);
$router->get('/participant/reload/{id}/{timespan?}', ['as' => 'reload', 'uses' => 'ParticipantController@reload']);


//dd($router);