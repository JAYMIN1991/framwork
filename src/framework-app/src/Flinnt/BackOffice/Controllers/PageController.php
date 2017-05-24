<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 26/10/16
 * Time: 11:24 AM
 */

namespace Flinnt\BackOffice\Controllers;


use Flinnt\BackOffice\Contracts\IPageController;
use Flinnt\Core\Collection\StronglyTypedCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class PageController
 *
 * @package Flinnt\BackOffice\Controllers
 */
class PageController extends BaseController implements IPageController
{

	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	const DEFAULT_SIDEBAR = "App\Common\View\Sidebars\TestViewSidebar";
	const DEFAULT_HEADER = "App\Common\View\Layouts\TestViewHeader";
	const DEFAULT_FOOTER = "App\Common\View\Layouts\TestViewFooter";

	protected $pageTitle;

	protected $header;

	protected $footer;

	protected $sidebar;
	protected $sidebarStatus = false;
	protected $customHeader = false;
	protected $customFooter = false;
	private $sidebarCollection;

	/**
	 * PageController constructor.
	 *
	 */
	public function __construct()
	{
		$this->sidebarCollection = new StronglyTypedCollection("Flinnt\Core\View\AbstractViewSidebar");
	}

	/**
	 * Set the content of sidebar
	 *
	 * @param $sidebar
	 *
	 * @return void
	 *
	 */
	public function addSidebar( $sidebar )
	{
		$this->sidebarCollection->add($sidebar);
	}

	/**
	 * Add the sidebar namespace to collection object
	 *
	 * @param $sidebar
	 *
	 * @return void
	 */
	public function removeSidebar( $sidebar )
	{
		$this->sidebarCollection->remove($sidebar);
	}

	/**
	 * Get the title of the page
	 *
	 * @return String
	 */
	public function getPageTitle()
	{

		return $this->pageTitle;
	}

	/**
	 * Set the title of the page
	 *
	 * @param  String $title
	 *
	 * @return  self
	 */
	public function setPageTitle( $title )
	{

		$this->pageTitle = $title;

		return $this;
	}

	/**
	 * Get the content of header
	 *
	 * @return String
	 *
	 */
	public function getHeader()
	{

		return $this->header;
	}

	/**
	 * Set the content of header
	 *
	 * @param  String $header
	 *
	 * @return  self
	 */
	public function setHeader( $header )
	{

		$this->header = $header;

		return $this;
	}

	/**
	 * Get the content of footer
	 *
	 * @return String
	 *
	 */
	public function getFooter()
	{

		return $this->footer;
	}

	/**
	 * Set the content of footer
	 *
	 * @param  String $footer
	 *
	 * @return  self
	 */
	public function setFooter( $footer )
	{

		$this->footer = $footer;

		return $this;
	}

	/**
	 * Get the status of sidebar
	 *
	 * @return bool
	 */
	public function getSidebarStatus()
	{

		return $this->sidebarStatus;
	}

	/**
	 * Set the status of sidebar
	 *
	 * @param  bool $status
	 *
	 * @return  self
	 */
	public function setSidebarStatus( $status )
	{

		$this->sidebarStatus = $status;

		return $this;
	}

	/**
	 * Create the response and send it in the response
	 *
	 * @param       $viewName
	 * @param array $data
	 *
	 * @return mixed
	 */
	public function showResponse( $viewName, $data = [] )
	{
		$config = $data;

		if ( ! $this->sidebarStatus ) {
			$class = self::DEFAULT_SIDEBAR;
			$sidebar = new $class($this, ["testSidebar" => "Test Sidebar from hello"]);
			$config = array_merge(["bodySidebar" => $sidebar->getContent()], $config);
		}
		else {
			$sidebar = "";
			foreach ( $this->sidebarCollection as $sidebarObj ) {
				$sidebar .= $sidebarObj->getContent();
			}
			$config = array_merge(["bodySidebar" => $sidebar]);
		}
		if ( ! $this->customHeader ) {
			$class = self::DEFAULT_HEADER;
			$header = new $class($this, ["testSidebar" => "Test Header from hello"]);
			$config = array_merge(["bodyHeader" => $header->getContent()], $config);
		}
		else {
			$header = new $this->header($this);
			$config = array_merge(["bodyHeader" => $header->getContent()], $config);
		}
		if ( ! $this->customFooter ) {
			$class = self::DEFAULT_FOOTER;
			$header = new $class($this, ["testSidebar" => "Test Footer from hello"]);
			$config = array_merge(["bodyFooter" => $header->getContent()], $config);
		}
		else {
			$header = new $this->header($this);
			$config = array_merge(["bodyFooter" => $header->getContent()], $config);
		}

		return response()->view($viewName, $config);
	}
}