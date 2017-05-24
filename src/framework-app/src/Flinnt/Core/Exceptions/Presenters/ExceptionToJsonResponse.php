<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 8/2/17
 * Time: 12:18 PM
 */

namespace Flinnt\Core\Exceptions\Presenters;

use Exception;
use Flinnt\Core\Exceptions\BaseException;
use Flinnt\Core\Exceptions\Error;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Class ExceptionToJsonResponse
 * @package Flinnt\Core\Exceptions\Presenters
 */
class ExceptionToJsonResponse extends BaseResponsePresenter {

	/**
	 * Render the base exception
	 *
	 * @param BaseException $exception Exception class which you want to render
	 * @param int           $status    HTTP response status
	 *
	 * @return JsonResponse
	 */
	protected function parseBaseException( BaseException $exception, $status ) {
		$error['status'] = 0;
		$error['message'] = $exception->getMessage();
		if ( isset($exception->getError()[0]) ) {
			/** @var Error $e */
			foreach ( $exception->getError() as $e ) {
				$error['errors'][] = $e->toArray();
			}
		}

		return response()->json($error, $status);
	}

	/**
	 * Render the default exception
	 *
	 * @param \Exception $exception Occurred Exception
	 * @param int        $status    HTTP response status
	 *
	 * @return JsonResponse
	 */
	protected function parseDefaultException( Exception $exception, $status ) {
		$error['status'] = 0;
		$error['message'] = trans('exception.something_wrong.message');
		$error['errors'] = $this->getErrorFromException($exception);

		return response()->json($error, $status);
	}

	/**
	 * Render the unauthenticated exception
	 *
	 * @param \Illuminate\Auth\AuthenticationException $exception Exception class which you want to render
	 * @param int                                      $status    HTTP response status
	 *
	 * @return mixed
	 */
	protected function parseAuthenticationException( AuthenticationException $exception, $status ) {
		return response()->json(['status' => 0, 'message' => trans('shared::message.error.unauthenticated')], 401);
	}

	/**
	 * Render the default exception
	 *
	 * @param ValidationException $exception Exception class which you want to render
	 * @param int                 $status    HTTP response status
	 *
	 * @return mixed
	 */
	protected function parseValidationException( ValidationException $exception, $status ) {
		if ( $exception->response ) {
			return $exception->response;
		}

		$errors = $exception->validator->errors()->getMessages();

		return response()->json($errors, 422);
	}


}