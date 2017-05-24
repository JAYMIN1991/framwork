<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/11/16
 * Time: 12:29 PM
 */

namespace Flinnt\Core\SMS\Providers;


use Flinnt\Core\SMS\SMSHandler;
use Flinnt\Core\SMS\Transport\MobisoftTransport;
use Illuminate\Support\ServiceProvider;
use Mobisoft\SMS\API\MobiSoft;

/**
 * Class SMSServiceProvider
 *
 * @package Flinnt\Core\SMS\Provider
 */
class SMSServiceProvider extends ServiceProvider
{

	protected $defer = true;

	/**
	 * register the SMSHandler with laravel application.
	 */
	public function register()
	{
		$this->app->singleton("sms", function ( $app ) {
			$config = config('services.mobisoft.general');

			$transport = new MobisoftTransport($config['username'], $config['password'], $config['gsm'], $config['url']);

			$smsHandler = new SMSHandler($transport, $app['events']);

			if ( $app->bound('queue') ) {
				$smsHandler->setQueue($app["queue"]);
			}

			return $smsHandler;
		});
	}

	/**
	 * Initialize the MobiSoft API to send SMS.
	 *
	 * @param MobiSoft $mobisoft
	 * @param array    $config
	 */
	public function initializeMobisoft( $mobisoft, $config )
	{
		$headers = [];
		$headers[] = 'Content-Type: text/xml; charset=UTF-8';
		$headers[] = "SOAPAction: " . $config['url'];
		$mobisoft->setParameters($config['username'], $config['password'], $config['gsm'], $config['url']);
		$mobisoft->setHeaders($headers);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['sms'];
	}


}