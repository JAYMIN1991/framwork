<?php

namespace Flinnt\Core\View\Widgets\Console;


use Illuminate\Support\Str;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class WidgetMakeCommand extends GeneratorCommand
{

	use AppNamespaceDetectorTrait;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'make:widget';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new widget (flinnt/core/view/widgets)';

	/**
	 * The type of class being generated.
	 *
	 * @var string
	 */
	protected $type = 'Widget';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{

		parent::fire();

		if ( ! $this->option('plain') ) {
			$this->createView();
		}
	}

	/**
	 * Create a new view file for the widget.
	 *
	 * return void
	 */
	protected function createView()
	{
		if ( $this->files->exists($path = $this->getViewPath()) ) {

			$this->error('View already exists!');

			return;
		}

		$this->makeDirectory($path);

		$this->files->put($path, '');

		$this->info('View created successfully.');
	}

	/**
	 * Get the destination view path.
	 *
	 * @return string
	 */
	protected function getViewPath()
	{
		/* flint comment
		return base_path('resources/views').'/widgets/'.$this->makeViewName().'.blade.php'; */


		if ( $this->option('module') != "" ) {
			return base_path('app/Modules/' . (ucfirst($this->option('module'))) . '/Resources/Views') . '/widgets/' . $this->makeViewName() . '.twig';
		}
		else {
			return base_path('resources/views') . '/widgets/' . $this->makeViewName() . '.twig';
		}

	}

	/**
	 * Get the destination view name without extensions.
	 *
	 * @return string
	 */
	protected function makeViewName()
	{
		$name = str_replace($this->getAppNamespace(), '', $this->argument('name'));
		$name = str_replace('\\', '/', $name);

		// convert to snake_case part by part to avoid unexpected underscores.
		$nameArray = explode('/', $name);
		array_walk($nameArray, function ( &$part ) {
			$part = snake_case($part);
		});

		return implode('/', $nameArray);
	}

	/**
	 * Build the class with the given name.
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	protected function buildClass( $name )
	{
		$stub = $this->files->get($this->getStub());

		$stub = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

		if ( ! $this->option('plain') ) {
			$stub = $this->replaceView($stub);
		}

		return $stub;
	}

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	protected function getStub()
	{
		$stubName = $this->option('plain') ? 'widget_plain' : 'widget';
		$stubPath = $this->laravel->make('config')->get('widgets.' . $stubName . '_stub');

		// for BC
		if ( is_null($stubPath) ) {
			return __DIR__ . '/stubs/' . $stubName . '.stub';
		}

		return $this->laravel->basePath() . '/' . $stubPath;
	}

	/**
	 * Replace the class name for the given stub.
	 *
	 * @param string $stub
	 * @param string $name
	 *
	 * @return string
	 */
	protected function replaceClass( $stub, $name )
	{
		$class = str_replace($this->getNamespace($name) . '\\', '', $name);

		return str_replace('{{class}}', $class, $stub);
	}

	/**
	 * Replace the namespace for the given stub.
	 *
	 * @param string $stub
	 * @param string $name
	 *
	 * @return $this
	 */
	protected function replaceNamespace( &$stub, $name )
	{

		$stub = str_replace('{{namespace}}', $this->getNamespace($name), $stub);

		$stub = str_replace('{{rootNamespace}}', $this->getAppNamespace(), $stub);

		return $this;
	}

	/**
	 * Replace the view name for the given stub.
	 *
	 * @param string $stub
	 *
	 * @return string
	 */
	protected function replaceView( $stub )
	{
		$view = "";

		if ( $this->option('module') != "" ) {
			$view = strtolower($this->option('module')) . '::' . 'widgets.' . str_replace('/', '.', $this->makeViewName());
		}
		else {
			$view = 'widgets.' . str_replace('/', '.', $this->makeViewName());
		}

		return str_replace('{{view}}', $view, $stub);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [['plain', null, InputOption::VALUE_NONE, 'Use plain stub. No view is being created too.'], ['module', null, InputOption::VALUE_OPTIONAL, 'Give module name to create within Module path.']];
	}

	/**
	 * Parse the name and format according to the root namespace.
	 *
	 * @param  string $name
	 *
	 * @return string
	 */
	protected function parseName( $name )
	{


		if ( $this->isWithinModule() ) {

			$rootNamespace = $this->getDefaultModuleBasedNamespace(''); //config('modules.namespace') . (ucfirst($this->option('module')));
		}

		else {
			$rootNamespace = $this->laravel->getNamespace();
		}


		if ( Str::startsWith($name, $rootNamespace) ) {
			return $name;
		}

		if ( Str::contains($name, '/') ) {
			$name = str_replace('/', '\\', $name);
		}

		return $this->parseName($this->getDefaultNamespace(trim($rootNamespace, '\\')) . '\\' . $name);
	}

	/**
	 * Check if Widget need to be created within module
	 * @return bool
	 */
	protected function isWithinModule()
	{
		if ( $this->option('module') != "" ) {
			return true;
		}

		return false;
	}

	protected function getDefaultModuleBasedNamespace( $rootNamespace )
	{
		$default_namesapce = config('modules.default_namespace_in_module', '{{default_module_namespace}}\Http\Widgets');

		/* calling cafinated facade to access module */

		//$module = new Modules($this->getApplication(),$this->getApplication()->make( \Caffeinated\Modules\Contracts\Repository::class));
		//$module    = Modules::where('slug',strtolower($this->option('module')));
		//$module['basename'];

		$module_namespace = config('modules.namespace', 'App\Modules\\') . (ucfirst($this->option('module')));

		$default_namesapce = str_replace("{{default_module_namespace}}", $module_namespace, $default_namesapce);

		return $default_namesapce;

	}

	/**
	 * Get the default namespace for the class.
	 *
	 * @param string $rootNamespace
	 *
	 * @return string
	 */
	protected function getDefaultNamespace( $rootNamespace )
	{
		if ( $this->isWithinModule() ) {
			return $this->getDefaultModuleBasedNamespace($rootNamespace);
		}

		return config('widgets.default_namespace', $rootNamespace . '\Http\Widgets');
	}


}
