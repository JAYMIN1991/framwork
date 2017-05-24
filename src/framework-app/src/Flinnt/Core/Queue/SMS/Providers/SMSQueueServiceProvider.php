<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/11/16
 * Time: 3:14 PM
 */

namespace Flinnt\Core\Queue\SMS\Providers;


use Flinnt\Core\Queue\SMS\Connectors\SMSConnector;
use Illuminate\Support\ServiceProvider;

/**
 * Class SMSQueueServiceProvider
 *
 * @package Flinnt\Core\Queue\SMS\Providers
 */
class SMSQueueServiceProvider extends ServiceProvider
{

	/**
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Extend the queue to handle the custom sms connector.
	 */
	public function register()
	{
		$this->app->booted(function () {
			$this->app['queue']->extend('sms', function () {
				return new SMSConnector($this->app['db']);
			});
		});
	}
}