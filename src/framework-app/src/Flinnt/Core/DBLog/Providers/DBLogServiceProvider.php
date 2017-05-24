<?php

namespace Flinnt\Core\DBLog\Providers;

use Flinnt\Core\DBLog\DBLog;
use Illuminate\Support\ServiceProvider;
use App;

/**
 * Class DBLogServiceProvider
 *
 * @package Flinnt\Core\DBLog\Providers
 */
class DBLogServiceProvider extends ServiceProvider
{

	protected $defer = false;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		App::bind('dblog', function () {
			return DBLog::getInstance();
		});
	}
}
