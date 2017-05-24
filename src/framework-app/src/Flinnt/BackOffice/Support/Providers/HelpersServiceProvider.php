<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/11/16
 * Time: 12:29 PM
 */

namespace Flinnt\BackOffice\Support\Providers;


use Flinnt\BackOffice\Support\Helpers;

use Illuminate\Support\ServiceProvider;


/**
 * Class SMSServiceProvider
 *
 * @package Flinnt\Core\SMS\Provider
 */
class HelpersServiceProvider extends ServiceProvider
{

	protected $defer = true;

	/**
	 * register the SMSHandler with laravel application.
	 */
	public function register()
	{
		$this->app->singleton("backofficehelper", function ( $app ) {


			$helper = new Helpers();

			return $helper;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{

		return ['backofficehelper'];
	}


}