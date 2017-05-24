<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 8/2/17
 * Time: 12:15 PM
 */

namespace Flinnt\Core\Contracts\Exception;

/**
 * Interface ExceptionResponsePresenter
 * @package App\Exceptions\Presenters
 */
interface ExceptionResponsePresenter {

	/**
	 * Render the exception
	 * @return mixed
	 *
	 */
	public function renderResponse();
}