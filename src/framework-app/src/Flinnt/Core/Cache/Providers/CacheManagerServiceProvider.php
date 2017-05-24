<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 22/11/16
 * Time: 3:10 PM
 */

namespace Flinnt\Core\Cache\Providers;


use Flinnt\Core\Cache\CacheManager;
use Illuminate\Support\ServiceProvider;

/**
 * Class CacheManagerServiceProvider
 *
 * @package Flinnt\Core\Cache\Providers
 */
class CacheManagerServiceProvider extends ServiceProvider
{
	/**
	 * @var bool
	 */
	protected $defer = true;


	/**
	 * register the cachemanager with laravel service container
	 *
	 */
	public function register(){
		$this->app->singleton('cachemanager', function(){
			return new CacheManager();
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['cachemanager'];
	}


}