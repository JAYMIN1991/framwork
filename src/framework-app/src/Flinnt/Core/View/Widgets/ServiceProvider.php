<?php

namespace Flinnt\Core\View\Widgets;

use Flinnt\Core\View\Widgets\Console\WidgetMakeCommand;
use Flinnt\Core\View\Widgets\Factories\AsyncWidgetFactory;
use Flinnt\Core\View\Widgets\Factories\WidgetFactory;
use Flinnt\Core\View\Widgets\Misc\LaravelApplicationWrapper;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Support\Facades\Blade;

/**
 * Class ServiceProvider
 *
 * @package Flinnt\Core\View\Widgets
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

	use AppNamespaceDetectorTrait;

	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'widgets');

		$this->app->bind('flinnt.core.view.widget', function () {
			return new WidgetFactory(new LaravelApplicationWrapper());
		});

		$this->app->bind('flinnt.core.view.async-widget', function () {
			return new AsyncWidgetFactory(new LaravelApplicationWrapper());
		});

		$this->app->singleton('flinnt.core.view.widget-group-collection', function () {
			return new WidgetGroupCollection(new LaravelApplicationWrapper());
		});

		$this->app->singleton('command.widget.make', function ( $app ) {
			return new WidgetMakeCommand($app['files']);
		});

		$this->commands('command.widget.make');

		$this->app->alias('flinnt.core.view.widget', 'Flinnt\Core\View\Widgets\Factories\WidgetFactory');
		$this->app->alias('flinnt.core.view.async-widget', 'Flinnt\Core\View\Widgets\Factories\AsyncWidgetFactory');
		$this->app->alias('flinnt.core.view.widget-group-collection', 'Flinnt\Core\View\Widgets\WidgetGroupCollection');
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([__DIR__ . '/config/config.php' => config_path('widgets.php'),]);

		$routeConfig = ['namespace' => 'Flinnt\Core\View\Widgets\Controllers', 'prefix' => 'flinnt', 'middleware' => $this->app['config']->get('widgets.route_middleware', []),];

		if ( ! $this->app->routesAreCached() ) {
			$this->app['router']->group($routeConfig, function ( $router ) {
				$router->get('load-widget', 'WidgetController@showWidget');
			});
		}

		$omitParenthesis = version_compare($this->app->version(), '5.3', '<');

		Blade::directive('widget', function ( $expression ) use ( $omitParenthesis ) {
			$expression = $omitParenthesis ? $expression : "($expression)";

			return "<?php echo app('flinnt.core.view.widget')->run{$expression}; ?>";
		});

		Blade::directive('asyncWidget', function ( $expression ) use ( $omitParenthesis ) {
			$expression = $omitParenthesis ? $expression : "($expression)";

			return "<?php echo app('flinnt.core.view.async-widget')->run{$expression}; ?>";
		});

		Blade::directive('widgetGroup', function ( $expression ) use ( $omitParenthesis ) {
			$expression = $omitParenthesis ? $expression : "($expression)";

			return "<?php echo app('flinnt.core.view.widget-group-collection')->group{$expression}->display(); ?>";
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['flinnt.core.view.widget', 'flinnt.core.view.async-widget'];
	}
}
