<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 8/2/17
 * Time: 12:21 PM
 */

namespace Flinnt\Core\Exceptions\Presenters;

use Exception;
use Flinnt\Core\Contracts\Exception\ExceptionResponsePresenter;
use Flinnt\Core\Exceptions\BaseException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

/**
 * Class BaseResponsePresenter
 * @package Flinnt\Core\Exceptions\Presenters
 */
abstract class BaseResponsePresenter implements ExceptionResponsePresenter {

	/**
	 * @var BaseException|\Exception|ValidationException|AuthenticationException
	 */
	protected $exception;

	/**
	 * BaseResponsePresenter constructor.
	 *
	 * @param \Exception $exception Exception which you want to render
	 */
	public function __construct( Exception $exception ) {
		$this->exception = $exception;
	}


	/**
	 * Render the base exception
	 *
	 * @param BaseException $exception Exception class which you want to render
	 *
	 * @param int           $status    HTTP response status
	 *
	 * @return mixed
	 */
	abstract protected function parseBaseException( BaseException $exception, $status );

	/**
	 * Render the validation exception
	 *
	 * @param \Illuminate\Validation\ValidationException $exception Exception class which you want to render
	 * @param int                                        $status    HTTP response status
	 *
	 * @return mixed
	 */
	abstract protected function parseValidationException( ValidationException $exception, $status );

	/**
	 * Render the unauthenticated exception
	 *
	 * @param \Illuminate\Auth\AuthenticationException $exception Exception class which you want to render
	 * @param int                                      $status    HTTP response status
	 *
	 * @return mixed
	 */
	abstract protected function parseAuthenticationException( AuthenticationException $exception, $status );

	/**
	 * Render the default exception
	 *
	 * @param \Exception $exception Exception class which you want to render
	 * @param int        $status    HTTP response status
	 *
	 * @return mixed
	 */
	abstract protected function parseDefaultException( Exception $exception, $status );

	/**
	 * Render the exception
	 *
	 * @return mixed
	 */
	public function renderResponse() {
		switch ( $this->exception ) {
			case $this->exception instanceof BaseException :
				return $this->parseBaseException($this->exception, $this->exception->getCode());
				break;
			case $this->exception instanceof ValidationException :
				return $this->parseValidationException($this->exception, $this->exception->getCode());
				break;
			case $this->exception instanceof AuthenticationException :
				return $this->parseAuthenticationException($this->exception, $this->exception->getCode());
				break;
			default :
				return $this->parseDefaultException($this->exception, 577);
		}
	}

	/**
	 * Get the error array from exception
	 *
	 * @param \Exception $exception Occurred exception
	 *
	 * @return mixed Array of error
	 */
	protected function getErrorFromException( Exception $exception ) {
		$error['code'] = $exception->getCode();
		$error['message'] = $exception->getMessage();
		$error['file'] = $exception->getFile();
		$error['line'] = $exception->getLine();
		$error['trace'] = $exception->getTraceAsString();
		$error['type'] = get_class($exception);

		return $error;
	}
}