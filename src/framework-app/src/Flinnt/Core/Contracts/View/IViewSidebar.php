<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 26/10/16
 * Time: 5:06 PM
 */

namespace Flinnt\Core\Contracts\View;



/**
 * Interface IViewSidebar
 * @package Flinnt\Core\Contracts\View
 */
interface IViewSidebar extends IView
{
	/**
	 * Return the content of sidebar
	 *
	 * @return String
	 *
	 */
	public function getContent();

	/**
	 * Check if sidebar has content or not
	 *
	 * @return bool
	 *
	 */
	public function hasContent();
}