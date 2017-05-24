<?php

namespace Flinnt\Core\View\Widgets\Factories;

use Flinnt\Core\View\Widgets\AbstractWidget;
use Flinnt\Core\View\Widgets\Contracts\ApplicationWrapperContract;
use Flinnt\Core\View\Widgets\Misc\InvalidWidgetClassException;
use Flinnt\Core\View\Widgets\Misc\ViewExpressionTrait;
use Flinnt\Core\View\Widgets\WidgetId;

/**
 * Class AbstractWidgetFactory
 *
 * @package Flinnt\Core\View\Widgets\Factories
 */
abstract class AbstractWidgetFactory
{

	use ViewExpressionTrait;

	/**
	 * The flag for not wrapping content in a special container.
	 *
	 * @var bool
	 */
	public static $skipWidgetContainer = false;
	/**
	 * The ajaxLink for reloadable widget
	 *
	 * @var bool|string
	 */
	public $ajaxLink = false;
	/**
	 * The name of the widget being called.
	 *
	 * @var string
	 */
	public $widgetName;
	/**
	 * Array of widget parameters excluding the first one (config).
	 *
	 * @var array
	 */
	public $widgetParams;
	/**
	 * Array of widget parameters including the first one (config).
	 *
	 * @var array
	 */
	public $widgetFullParams;
	/**
	 * Laravel application wrapper for better testability.
	 *
	 * @var ApplicationWrapperContract;
	 */
	public $app;
	/**
	 * Widget object to work with.
	 *
	 * @var AbstractWidget
	 */
	protected $widget;
	/**
	 * Widget configuration array.
	 *
	 * @var array
	 */
	protected $widgetConfig;
	/**
	 * Another factory that produces some javascript.
	 *
	 * @var JavascriptFactory
	 */
	protected $javascriptFactory;

	/**
	 * Constructor.
	 *
	 * @param ApplicationWrapperContract $app
	 */
	public function __construct( ApplicationWrapperContract $app )
	{
		$this->app = $app;

		//commented below line to construct it after widget construction
		//$this->javascriptFactory = new JavascriptFactory($this);
	}

	/**
	 * Magic method that catches all widget calls.
	 *
	 * @param string $widgetName
	 * @param array  $params
	 *
	 * @return mixed
	 */
	public function __call( $widgetName, array $params = [] )
	{
		array_unshift($params, $widgetName);

		return call_user_func_array([$this, 'run'], $params);
	}

	/**
	 * Encrypt widget params to be transported via HTTP.
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public function encryptWidgetParams( $params )
	{
		// return $this->app->make('encrypter')->encrypt(json_encode($params));
		return json_encode($params);
	}

	/**
	 * Decrypt widget params that were transported via HTTP.
	 *
	 * @param string $params
	 *
	 * @return array
	 */
	public function decryptWidgetParams( $params )
	{
		// $params = json_decode($this->app->make('encrypter')->decrypt($params), true);
		$params = json_decode($params);

		return $params ? $params : [];
	}

	/**
	 * Set class properties and instantiate a widget object.
	 *
	 * @param $params
	 *
	 * @throws InvalidWidgetClassException
	 */
	protected function instantiateWidget( array $params = [] )
	{
		WidgetId::increment();


		$this->widgetName = $this->parseFullWidgetNameFromString(array_shift($params));


		$this->widgetFullParams = $params;
		$this->widgetConfig = (array) array_shift($params);
		$this->widgetParams = $params;

		//var_dump($params);

		$rootNamespace = $this->app->config('widgets.default_namespace', $this->app->getNamespace() . 'Widgets');

		$fqcn = $rootNamespace . '\\' . $this->widgetName;
		$widgetClass = class_exists($fqcn) ? $fqcn : $this->widgetName;

		if ( ! is_subclass_of($widgetClass, 'Flinnt\Core\View\Widgets\AbstractWidget') ) {
			throw new InvalidWidgetClassException('Class "' . $widgetClass . '" must extend "Flinnt\Core\View\Widgets\AbstractWidget" class');
		}

		$this->widget = new $widgetClass($this->widgetConfig);

		// var_dump($this->widget);

		$this->ajaxLink = $this->widget->ajaxLink;

		//updating javascript factory to pass ajaxlink
		$this->javascriptFactory = new JavascriptFactory($this);
	}

	/**
	 * Convert stuff like 'profile.feedWidget' to 'Profile\FeedWidget'.
	 *
	 * @param $widgetName
	 *
	 * @return string
	 */
	protected function parseFullWidgetNameFromString( $widgetName )
	{

		return studly_case(str_replace('.', '\\', $widgetName));

	}

	/**
	 * Wrap the given content in a container if it's not an ajax call.
	 *
	 * @param $content
	 *
	 * @return string
	 */
	protected function wrapContentInContainer( $content )
	{
		if ( self::$skipWidgetContainer ) {
			return $content;
		}

		$container = $this->widget->container();
		if ( empty($container['element']) ) {
			$container['element'] = 'div';
		}

		return '<' . $container['element'] . ' id="' . $this->javascriptFactory->getContainerId() . '" ' . $container['attributes'] . '>' . $content . '</' . $container['element'] . '>';
	}
}
