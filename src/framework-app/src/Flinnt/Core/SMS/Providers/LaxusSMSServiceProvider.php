<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 15/11/16
 * Time: 5:04 PM
 */

namespace Flinnt\Core\SMS\Providers;


use Flinnt\Core\SMS\SMSHandler;
use Flinnt\Core\SMS\Transport\TwilioTransport;
use Illuminate\Support\ServiceProvider;

/**
 * Class LaxusSMSServiceProvider
 *
 * @package Flinnt\Core\SMS\Providers
 */
class LaxusSMSServiceProvider extends ServiceProvider
{

	/**
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the laxusmail provider to laravel container
	 */
	public function register()
	{
		$this->app->singleton("laxussms", function ( $app ) {
			$config = config('services.twilio');

			$transport = new TwilioTransport($config['sid'], $config['authtoken'], $config['from']);

			$smsHandler = new SMSHandler($transport, $app['events']);

			if ( $app->bound('queue') ) {
				$smsHandler->setQueue($app["queue"]);
			}

			return $smsHandler;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ["laxussms"];
	}


}