<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 7/2/17
 * Time: 2:57 PM
 */

namespace Flinnt\Core\Exceptions;


use Exception;

/**
 * Class BaseException
 * @package Flinnt\Core\Exceptions
 */
class BaseException extends Exception {

	/**
	 * @var array Array of error object
	 */
	private $error;

	/**
	 * BaseException constructor.
	 *
	 * @param string $code             Code of the exception
	 * @param int    $errorMessage     Message that will be shown in error object
	 * @param string $userMessage      Exception message. This will be shown to user
	 *                                 If not specified, message of error object will be shown to user
	 * @param int    $responseStatus   Status of the response
	 *
	 */
	public function __construct( $code, $errorMessage, $userMessage = '', $responseStatus = 400 ) {
		$message = $userMessage;
		$this->setError($code, $errorMessage);

		// Construct exception with error message if user message is not specified
		if ( empty($message) ) {
			$message = $errorMessage;
		}

		parent::__construct($message, $responseStatus);
	}

	/**
	 * Get the array of error object
	 *
	 * @return array
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * Set the error object
	 *
	 * @param int    $code    Error code
	 * @param string $message Error message
	 *
	 */
	public function setError( $code, $message ) {
		$e = null;

		// Exception trace is added to error if called first time
		if ( empty($this->error) ) {
			$e = $this;
		}

		$this->error[] = new Error($code, $message, $e);
	}
}