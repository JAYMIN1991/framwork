<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 7/2/17
 * Time: 3:00 PM
 */

namespace Flinnt\Core\Exceptions;


use App;
use Exception;
use Illuminate\Support\Str;

/**
 * Class Error
 * @package Flinnt\Core\Exceptions
 */
class Error {

	/**
	 * @var int Code of the exception
	 */
	private $code;

	/**
	 * @var string Message based on code
	 */
	private $message;

	/**
	 * @var Exception Exception for which error occurred
	 */
	private $exception = null;

	/**
	 * Error constructor.
	 *
	 * @param int        $code
	 * @param string     $message
	 * @param \Exception $exception
	 *
	 */
	public function __construct( $code, $message, Exception $exception = null ) {
		$this->setCode($code);
		$this->setMessage($message);
		$this->setException($exception);
	}

	/**
	 * Get the code of the exception
	 *
	 * @return mixed
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * Set the code of the exception
	 *
	 * @param mixed $code
	 */
	public function setCode( $code ) {
		$this->code = $code;
	}

	/**
	 * Get the message of exception
	 * @return mixed
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Set the message of exception
	 *
	 * @param mixed $message
	 */
	public function setMessage( $message ) {
		$this->message = $message;
	}

	/**
	 * Get the exception
	 * @return \Exception
	 */
	public function getException() {
		return $this->exception;
	}

	/**
	 * Set the exception
	 *
	 * @param \Exception $exception
	 */
	public function setException( $exception ) {
		$this->exception = $exception;
	}

	/**
	 * Get the error as array
	 *
	 * @return array
	 */
	public function toArray() {
		$error = [];
		$error['code'] = $this->getCode();
		$error['message'] = $this->getMessage();

		// If current environment is local or exception is null, we will not set the exception
		if ( strtolower(App::environment()) == APP_ENV_LOCAL && ! is_null($this->exception) ) {
			$error['file'] = $this->exception->getFile();
			$error['line'] = $this->exception->getLine();
			$error['trace'] = $this->exception->getTraceAsString();
			$error['type'] = get_class($this->exception);
		}

		return $error;
	}
}