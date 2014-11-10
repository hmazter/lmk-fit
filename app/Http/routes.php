<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*
 * HomeController
 */
$router->get('/', 'HomeController@index');
$router->get('/add', 'HomeController@getAdd');
$router->post('/add', 'HomeController@postAdd');
$router->get('/code', 'HomeController@code');
$router->get('/about', 'HomeController@about');

/*
 * ParticipantController
 */
$router->get('/participants', 'ParticipantController@index');
$router->get('/participant/reload/{id}/{timespan?}', 'ParticipantController@reload');

/*
|--------------------------------------------------------------------------
| Authentication & Password Reset Controllers
|--------------------------------------------------------------------------
|
| These two controllers handle the authentication of the users of your
| application, as well as the functions necessary for resetting the
| passwords for your users. You may modify or remove these files.
|
*/

$router->controller('auth', 'AuthController');

$router->controller('password', 'PasswordController');
