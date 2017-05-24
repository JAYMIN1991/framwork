<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 26/10/16
 * Time: 5:59 PM
 */

namespace Flinnt\Core\Contracts\View;


/**
 * For rendering header or footer layout
 *
 * Interface IViewLayout
 * @package Flinnt\Core\Contracts\View
 */
interface IViewLayout extends IView
{
	/**
	 * Return the content of header or footer
	 *
	 * @return String
	 *
	 */
	public function getContent();
}