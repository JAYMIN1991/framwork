<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-8
 * Date: 7/11/16
 * Time: 2:28 PM
 */

namespace Flinnt\BackOffice\View\Engines\Twig\Extensions;

use Illuminate\Foundation\Application;
use Illuminate\Pagination\LengthAwarePaginator;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * This class is used for registering widget related function with twig.
 * like, widget, asyncWidget, widgetGroup, widgetGroupDisplay
 *
 * Class LaravelWidget
 * @package Flinnt\BackOffice\View\Engines\Twig\Extensions
 */
class Helper extends Twig_Extension {

	/**
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Create a new
	 *
	 * @param \Illuminate\Foundation\Application
	 */
	public function __construct( Application $app ) {
		$this->app = $app;
	}

	/**
	 * Returns array of functions registered with Twig
	 *
	 * @return array
	 */
	public function getFunctions() {
		return [
			new Twig_SimpleFunction('helper_*', function ( $name ) {
				$arguments = array_slice(func_get_args(), 1);

				return call_user_func_array([$this->app['backofficehelper'], $name], $arguments);
			}, ['is_safe' => ['html']]),

			new Twig_SimpleFunction('paginator_status', [$this, "paginatorStatus"], ['is_safe' => ['html']])
		];
	}

	/**
	 * Returns Name of Twig Extension
	 *
	 * @return string
	 */
	public function getName() {
		return 'TwigBridge_Extension_BackOfficeHelper';
	}

	/**
	 * This method will return status string of lengthAwarePaginator instance
	 *
	 * @param LengthAwarePaginator $lengthAwarePaginator Instance of LengthAwarePaginator
	 *
	 * @return string This will return status string, or blank if total is zero
	 */
	public function paginatorStatus( $lengthAwarePaginator ) {
		if ( $lengthAwarePaginator instanceof LengthAwarePaginator ) {
			if ( $lengthAwarePaginator->total() <= 0 ) {
				return "";
			}
			//TODO:: Vendor publish language action is pending, please implement vendor publish for lang
			$output = trans('shared::pagination.lap.displaying');
			$output .= ' <b> ' . (($lengthAwarePaginator->currentPage() - 1) * $lengthAwarePaginator->perPage() + 1) . '</b>';
			$output .= ' ' . trans('shared::pagination.lap.to') . ' <b>';

			if ( ($lengthAwarePaginator->perPage() * $lengthAwarePaginator->currentPage() > $lengthAwarePaginator->total()) ) {
				$output .= $lengthAwarePaginator->total();
			} else {
				$output .= $lengthAwarePaginator->perPage() * $lengthAwarePaginator->currentPage();
			}
			$output .= ' </b> (' . trans('shared::pagination.lap.of') . ' <b>' . $lengthAwarePaginator->total() . ' </b> ' . trans('shared::pagination.lap.records') . ')';

			return $output;
		} else {
			return trans('shared::pagination.lap.invalid');
		}
	}
}