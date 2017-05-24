<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/2/17
 * Time: 11:27 AM
 */

namespace Flinnt\Core\Console;


use Flinnt\Core\Console\Mail\ShutDownHandlerMail;
use Illuminate\Console\Command;

/**
 * Class BaseCommand
 * @package Flinnt\Core\Console
 */
class BaseCommand extends Command {


	/**
	 * Subject of the email
	 * Override this variable if you want to change the email of subject
	 *
	 * @var string
	 */
	protected $subject;

	/**
	 * Create a new console command instance.
	 */
	public function __construct() {
		parent::__construct();
		set_error_handler(null);
		set_exception_handler(null);
		register_shutdown_function([$this, 'shutDownHandler']);
	}

	/**
	 * Register the error shutdown handler for artisan command
	 */
	public function shutDownHandler(  ) {

		if ( ($errorDetail = error_get_last()) !== NULL ) {
			if ( in_array($errorDetail['type'],
				[E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR]) ) {

				// If subject is not provided change it to following
				if ($this->subject == 'PLEASE PROVIDE SUBJECT OF EMAIL HERE') {
					$this->subject = 'Cron\Job failed - ' . get_class($this);
				}

				$message = $errorDetail['message'];
				$errorDetail['errorMessage'] = $message;
				unset($errorDetail['message']);
				$mail = new ShutDownHandlerMail($errorDetail);
				$mail->subject($this->subject);
				$mail->sendNow(['email' => env('ERROR_REPORTING_TO_EMAIL'), 'name' => env('ERROR_REPORTING_TO_NAME')]);
				$this->error('Error in ' . $errorDetail['file'] . " :\n" . $errorDetail['type'] . ' ' . $errorDetail['errorMessage'] . ' at ' . $errorDetail['line'] . "\n");
				$this->line(str_repeat('*', 80));
			}
		}
	}
}