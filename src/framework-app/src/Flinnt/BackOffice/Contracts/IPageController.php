<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 25/10/16
 * Time: 7:17 PM
 */

namespace Flinnt\BackOffice\Contracts;

use Flinnt\Core\Contracts\Controller\IController;

/**
 * Interface IPageController
 * @package Flinnt\BackOffice\Contracts
 */
interface IPageController extends IController
{

	/**
	 * Set the title of the page
	 *
	 * @param String $title
	 *
	 */
	public function setPageTitle( $title );

	/**
	 * Get the title of the page
	 *
	 * @return String
	 */
	public function getPageTitle();

	/**
	 * Set the content of header
	 *
	 * @param String $header
	 *
	 */
	public function setHeader( $header );

	/**
	 * Get the content of header
	 *
	 * @return String
	 *
	 */
	public function getHeader();

	/**
	 * Set the content of footer
	 *
	 * @param String $footer
	 *
	 */
	public function setFooter( $footer );

	/**
	 * Get the content of footer
	 *
	 * @return String
	 *
	 */
	public function getFooter();

	/**
	 * Set the content of sidebar
	 *
	 * @param $sidebar
	 *
	 * @return String
	 *
	 */
	public function addSidebar( $sidebar );

	/**
	 * Add the sidebar namespace to collection object
	 *
	 * @param $sidebar
	 *
	 * @return String
	 */
	public function removeSidebar( $sidebar );

	/**
	 * Remove
	 *
	 * @param bool $status
	 *
	 */
	public function setSidebarStatus( $status );

	/**
	 * Get the status of sidebar
	 *
	 * @return bool
	 */
	public function getSidebarStatus();

	/**
	 * Create the response and send it in the response
	 *
	 * @param $viewName
	 * @param $data
	 *
	 * @return mixed
	 */
	public function showResponse( $viewName, $data );
}