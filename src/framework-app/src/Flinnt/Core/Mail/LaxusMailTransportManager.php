<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 4/11/16
 * Time: 3:38 PM
 */

namespace Flinnt\Core\Mail;


use Illuminate\Support\Manager;
use Swift_SmtpTransport as SmtpTransport;

/**
 * Class LaxusMailTransportManager
 * @package Flinnt\Core\Mail
 */
class LaxusMailTransportManager extends Manager
{

	/**
	 * Get the default driver name.
	 *
	 * @return string
	 */
	public function getDefaultDriver()
	{
		return env('LAXUS_MAIL_DRIVER', 'gmail');
	}

	/**
	 * Create an instance for gmail swift transport driver
	 *
	 * @return \Swift_SmtpTransport
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function createGmailDriver()
	{
		$config = $this->app['config']->get('services.gmail', []);

		if ( ! count($config) ) {
			throw new \InvalidArgumentException("Gmail configuration is not provided. Add configuration to .env file.");
		}

		$transport = SmtpTransport::newInstance($config["host"], $config["port"]);

		if ( isset($config['encryption']) ) {
			$transport->setEncryption($config['encryption']);
		}

		if ( isset($config['username']) ) {
			$transport->setUsername($config['username']);

			$transport->setPassword($config['password']);
		}

		if ( isset($config['stream']) ) {
			$transport->setStreamOptions($config['stream']);
		}

		return $transport;
	}

}