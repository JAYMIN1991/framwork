<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/2/17
 * Time: 2:11 PM
 */

namespace Flinnt\Core\Console\Generators;


use Illuminate\Console\GeneratorCommand;

/**
 * Class MakeCommandGenerator
 * @package Flinnt\Core\Console\Generators
 */
class MakeCommandGenerator extends GeneratorCommand {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'flinnt:make:command
    	{name : The name of the mailable class}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new command class';

	/**
	 * String to store the command type.
	 *
	 * @var string
	 */
	protected $type = 'Flinnt command';

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	protected function getStub() {
		return __DIR__ . '/stubs/command.stub';
	}

	/**
	 * Get the default namespace for the class.
	 *
	 * @param  string $rootNamespace
	 *
	 * @return string
	 */
	protected function getDefaultNamespace( $rootNamespace ) {
		return $this->laravel->getNamespace() . 'Console';
	}
}