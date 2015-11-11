<?php
/** @var \Illuminate\Routing\Router $router */


/*
 * HomeController
 */
$router->get('/{type?}', ['as' => 'home', 'uses' => 'HomeController@index']);
$router->get('/about', ['as' => 'about', 'uses' => 'HomeController@about']);

/*
 * AuthController
 */
$router->get('/code', ['as' => 'code', 'uses' => 'AuthController@code']);
$router->get('/auth', ['as' => 'auth', 'uses' => 'AuthController@auth']);

/*
 * ParticipantController
 */
$router->get('/participants', ['as' => 'participants', 'uses' => 'ParticipantController@index']);
$router->get('/participant/reload/{participant}/{timespan?}', ['as' => 'reload', 'uses' => 'ParticipantController@reload']);
