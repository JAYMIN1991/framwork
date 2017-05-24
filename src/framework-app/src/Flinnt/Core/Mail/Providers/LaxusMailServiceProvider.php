<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 4/11/16
 * Time: 3:37 PM
 */

namespace Flinnt\Core\Mail\Providers;


use Illuminate\Mail\Mailer;
use Illuminate\Support\ServiceProvider;
use Swift_Mailer;
use Flinnt\Core\Mail\LaxusMailTransportManager;

/**
 * Class LaxusMailServiceProvider
 *
 * @package Flinnt\Core\Mail\Providers
 */
class LaxusMailServiceProvider extends ServiceProvider
{

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerLaxusMailer();

		$this->app->singleton('laxusmailer', function ( $app ) {

			$mailer = new Mailer($app['view'], $app['laxus.mailer'], $app['events']);

			$this->setMailerDependencies($mailer, $app);

			$from = $app['config']->get('services.' . $app['laxus.transport']->getDefaultDriver() . '.from', []);

			if ( is_array($from) && isset($from['address']) ) {
				$mailer->alwaysFrom($from['address'], $from['name']);
			}

			return $mailer;
		});
	}

	/**
	 * Initialize and bind the Swift_mailer instance with laravel application based on laxus transport driver.
	 *
	 * @return void
	 */
	protected function registerLaxusMailer()
	{
		$this->registerLaxusTransport();

		$this->app['laxus.mailer'] = $this->app->share(function ( $app ) {
			return new Swift_Mailer($app['laxus.transport']->driver());
		});
	}

	/**
	 * Initialize and bind the LaxusMailTransportManager instance with laravel application.
	 *
	 * @return void
	 */
	protected function registerLaxusTransport()
	{
		$this->app['laxus.transport'] = $this->app->share(function ( $app ) {
			return new LaxusMailTransportManager($app);
		});
	}

	/**
	 * Set a few dependencies on the mailer instance.
	 *
	 * @param  \Illuminate\Mail\Mailer            $mailer
	 * @param  \Illuminate\Foundation\Application $app
	 *
	 * @return void
	 */
	protected function setMailerDependencies( $mailer, $app )
	{
		$mailer->setContainer($app);

		if ( $app->bound('queue') ) {
			$mailer->setQueue($app['queue']);
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['laxusmailer', 'laxus.mailer', 'laxus.transport'];
	}
}