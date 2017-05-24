<?php

namespace Flinnt\BackOffice\Firewall\Providers;


use Flinnt\BackOffice\Firewall\Firewall;
use Illuminate\Support\ServiceProvider;

/**
 * Class FirewallServiceProvider
 *
 * @package Flinnt\BackOffice\Firewall\Providers
 */
class FirewallServiceProvider extends ServiceProvider
{

	protected $defer = true;

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
		/* $this->app->singleton('Firewall',function($app,$cfg){

		 }); */

		$this->app->bind('Firewall', Firewall::class);

		//App::singleton('Firewall', Firewall::class);

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('Firewall');
	}
}
