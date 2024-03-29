<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 5/12/16
 * Time: 7:11 PM
 */

namespace Flinnt\Core\Console\Generators;


use Caffeinated\Modules\Console\GeneratorCommand;

/**
 * Class MakeMailableCommand
 *
 * @package Flinnt\Core\Console\Generators
 */
class MakeMailableCommand extends GeneratorCommand
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'flinnt:make:mail
    	{slug : The slug of the module}
    	{name : The name of the mailable class}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new module mailable class';

	/**
	 * String to store the command type.
	 *
	 * @var string
	 */
	protected $type = 'Module mailable';

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	protected function getStub()
	{
		return __DIR__ . '/stubs/mailable.stub';
	}

	/**
	 * Get the default namespace for the class.
	 *
	 * @param  string $rootNamespace
	 *
	 * @return string
	 */
	protected function getDefaultNamespace( $rootNamespace )
	{
		return module_class($this->argument('slug'), 'Mail');
	}


}