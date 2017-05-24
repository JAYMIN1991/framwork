<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 2/12/16
 * Time: 7:02 PM
 */

namespace Flinnt\Core\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\ConfigureLogging as BaseConfigureLogging;
use Illuminate\Log\Writer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;

/**
 * Class ConfigureLogging
 *
 * @package Flinnt\Core\Bootstrap
 */
class ConfigureLogging extends BaseConfigureLogging
{

	/**
	 * Configure the Monolog handlers for the application.
	 *
	 * @param  \Illuminate\Contracts\Foundation\Application $app
	 * @param  \Illuminate\Log\Writer                       $log
	 *
	 * @return void
	 */
	protected function configureHandlers( Application $app, Writer $log )
	{
		$bubble = false;

		// Stream Handlers
		$errorStreamHandler = new StreamHandler(storage_path("logs/laravel_error.log"), Monolog::ERROR, $bubble);
		$warningStreamHandler = new StreamHandler(storage_path("logs/laravel_warning.log"), Monolog::WARNING, $bubble);
		$noticeStreamHandler = new StreamHandler(storage_path("logs/laravel_notice.log"), Monolog::NOTICE, $bubble);
		$infoStreamHandler = new StreamHandler(storage_path("logs/laravel_info.log"), Monolog::INFO, $bubble);


		// Get monolog instance and push handlers
		$monolog = $log->getMonolog();
		$monolog->pushHandler($infoStreamHandler);
		$monolog->pushHandler($noticeStreamHandler);
		$monolog->pushHandler($warningStreamHandler);
		$monolog->pushHandler($errorStreamHandler);

		$log->useDailyFiles($app->storagePath() . '/logs/daily.log');
	}

}