<?php namespace LMK\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * All of the application's route middleware keys.
	 *
	 * @var array
	 */
	protected $middleware = [
		'auth' => 'LMK\Http\Middleware\Authenticated',
		'auth.basic' => 'LMK\Http\Middleware\AuthenticatedWithBasicAuth',
		'guest' => 'LMK\Http\Middleware\IsGuest',
	];

	/**
	 * Called before routes are registered.
	 *
	 * Register any model bindings or pattern based filters.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function before(Router $router)
	{
		//
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => 'LMK\Http\Controllers'], function($router)
		{
			require app_path('Http/routes.php');
		});
	}

}
