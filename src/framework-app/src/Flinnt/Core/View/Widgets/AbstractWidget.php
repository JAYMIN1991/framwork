<?php

namespace Flinnt\Core\View\Widgets;

/**
 * Class AbstractWidget
 *
 * @package Flinnt\Core\View\Widgets
 */
abstract class AbstractWidget
{

	/**
	 * The ajaxLink for reloadable widget
	 * False means use global ajaxLink
	 *
	 * @var bool|string
	 */
	public $ajaxLink = false;

	/**
	 * The number of seconds before each reload.
	 * False means no reload at all.
	 *
	 * @var int|float|bool
	 */
	public $reloadTimeout = false;

	/**
	 * The number of minutes before cache expires.
	 * False means no caching at all.
	 *
	 * @var int|float|bool
	 */
	public $cacheTime = false;

	/**
	 * The configuration array.
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Constructor.
	 *
	 * @param array $config
	 */
	public function __construct( array $config = [] )
	{
		foreach ( $config as $key => $value ) {
			$this->config[$key] = $value;
		}
	}

	/**
	 * Placeholder for async widget.
	 * You can customize it by overwriting this method.
	 *
	 * @return string
	 */
	public function placeholder()
	{
		return '';
	}

	/**
	 * Async and reloadable widgets are wrapped in container.
	 * You can customize it by overriding this method.
	 *
	 * @return array
	 */
	public function container()
	{
		return ['element' => 'div', 'attributes' => 'style="display:inline" class="flinnt-widget-container"',];
	}

	/**
	 * Cache key that is used if caching is enabled.
	 *
	 * @param $params
	 *
	 * @return string
	 */
	public function cacheKey( array $params = [] )
	{
		return 'flinnt.core.view.widgets.' . serialize($params);
	}

	/**
	 * Add defaults to configuration array.
	 *
	 * @param array $defaults
	 */
	protected function addConfigDefaults( array $defaults )
	{
		$this->config = array_merge($this->config, $defaults);
	}
}
