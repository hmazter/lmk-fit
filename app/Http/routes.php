<?php
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
/*
$router->controller('auth', 'AuthController');

$router->controller('password', 'PasswordController');
*/