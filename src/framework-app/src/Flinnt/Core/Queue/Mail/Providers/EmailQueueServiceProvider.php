<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 7/11/16
 * Time: 11:48 AM
 */

namespace Flinnt\Core\Queue\Mail\Providers;


use Flinnt\Core\Queue\Mail\Connectors\EmailConnector;
use Illuminate\Support\ServiceProvider;

/**
 * Class EmailQueueServiceProvider
 *
 * @package Flinnt\Core\Queue\Mail\Providers
 */
class EmailQueueServiceProvider extends ServiceProvider
{

	/**
	 * @var bool
	 */
	protected $defer = true;

	/**
	 *  Extend the laravel queue with email connector
	 */
	public function register()
	{
		$this->app->booted(function () {
			$this->app['queue']->extend('email', function () {
				return new EmailConnector($this->app['db']);
			});
		});
	}

}