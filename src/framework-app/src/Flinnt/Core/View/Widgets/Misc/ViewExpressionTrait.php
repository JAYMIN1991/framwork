<?php

namespace Flinnt\Core\View\Widgets\Misc;

use Illuminate\Support\HtmlString;

/**
 * Class ViewExpressionTrait
 *
 * @package Flinnt\Core\View\Widgets\Misc
 */
trait ViewExpressionTrait
{

	/**
	 * Convert a given html to HtmlString object that was introduced in Laravel 5.1.
	 *
	 * @param string $html
	 *
	 * @return \Illuminate\Support\HtmlString|string
	 */
	protected function convertToViewExpression( $html )
	{
		if ( interface_exists('Illuminate\Contracts\Support\Htmlable') && class_exists('Illuminate\Support\HtmlString') ) {
			return new HtmlString($html);
		}

		return $html;
	}
}
