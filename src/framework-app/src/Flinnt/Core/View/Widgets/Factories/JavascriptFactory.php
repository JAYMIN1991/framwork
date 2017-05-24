<?php

namespace Flinnt\Core\View\Widgets\Factories;

use Flinnt\Core\View\Widgets\WidgetId;

/**
 * Class JavascriptFactory
 *
 * @package Flinnt\Core\View\Widgets\Factories
 */
class JavascriptFactory
{

	/**
	 * Widget factory object.
	 *
	 * @var AbstractWidgetFactory
	 */
	protected $widgetFactory;

	/**
	 * Ajax link where widget can grab content.
	 *
	 * @var string
	 */
	protected $ajaxLink = '';

	/**
	 * @param $widgetFactory
	 */
	public function __construct( AbstractWidgetFactory $widgetFactory )
	{
		$this->widgetFactory = $widgetFactory;

		if ( $this->widgetFactory->ajaxLink ) {
			$this->ajaxLink = $widgetFactory->ajaxLink;
		}
		else {
			$this->ajaxLink = config('widgets.ajaxLink', '/flinnt/load-widget');
		}
	}

	/**
	 * Construct javascript code to load the widget.
	 *
	 * @return string
	 */
	public function getLoader()
	{
		return '<script type="text/javascript">' . $this->constructAjaxCall() . '</script>';
	}

	/**
	 * Construct ajax call for loaders.
	 *
	 * @return string
	 */
	protected function constructAjaxCall()
	{
		$queryParams = ['id' => WidgetId::get(), 'name' => $this->widgetFactory->widgetName, 'params' => $this->widgetFactory->encryptWidgetParams($this->widgetFactory->widgetFullParams),];

		$url = $this->ajaxLink . '?' . http_build_query($queryParams);

		return $this->useJquery() ? $this->constructJqueryAjaxCall($url) : $this->constructNativeJsAjaxCall($url);
	}

	/**
	 * Determine what to use - jquery or native js.
	 *
	 * @return bool
	 */
	protected function useJquery()
	{
		return $this->widgetFactory->app->config('widgets.use_jquery_for_ajax_calls', false);
	}

	/**
	 * Construct ajax call with jquery.
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	protected function constructJqueryAjaxCall( $url )
	{
		$id = WidgetId::get();

		return "var widgetTimer{$id} = setInterval(function() {" . 'if (window.$) {' . "$('#{$this->getContainerId()}').load('{$url}');" . "clearInterval(widgetTimer{$id});" . '}' . '}, 100);';
	}

	/**
	 * Get the current widget container id.
	 *
	 * @return string
	 */
	public function getContainerId()
	{
		return 'flinnt-widget-container-' . WidgetId::get();
	}

	/**
	 * Construct ajax call without jquery.
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	protected function constructNativeJsAjaxCall( $url )
	{
		return 'setTimeout(function() {' . 'var xhr = new XMLHttpRequest();' . 'xhr.open("GET", "' . $url . '", true);' . 'xhr.onreadystatechange = function() {' . 'if(xhr.readyState == 4 && xhr.status == 200) {' . 'var container = document.getElementById("' . $this->getContainerId() . '");' . 'container.innerHTML = xhr.responseText;' . 'var scripts = container.getElementsByTagName("script");' . 'for(var i=0; i < scripts.length; i++) {' . 'if (window.execScript) {' . 'window.execScript(scripts[i].text);' . '} else {' . 'window["eval"].call(window, scripts[i].text);' . '}' . '}' . '}' . '};' . 'xhr.send();' . '}, 0);';
	}

	/**
	 * Construct javascript code to reload the widget.
	 *
	 * @param float|int $timeout
	 *
	 * @return string
	 */
	public function getReloader( $timeout )
	{
		$timeout = $timeout * 1000;

		return '<script type="text/javascript">' . 'setTimeout( function() {' . $this->constructAjaxCall() . '}, ' . $timeout . ')' . '</script>';
	}
}
