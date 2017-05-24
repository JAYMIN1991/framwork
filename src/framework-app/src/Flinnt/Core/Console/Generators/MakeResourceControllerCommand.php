<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 6/12/16
 * Time: 4:15 PM
 */

namespace Flinnt\Core\Console\Generators;


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class MakeResourceControllerCommand
 *
 * @package Flinnt\Core\Console\Generators
 */
class MakeResourceControllerCommand extends Command
{

	/**
	 * The name of command.
	 *
	 * @var string
	 */
	protected $name = 'make:module:resource';

	/**
	 * The description of command.
	 *
	 * @var string
	 */
	protected $description = 'Create a new RESTfull controller.';

	/**
	 * The type of class being generated.
	 *
	 * @var string
	 */
	protected $type = 'Controller';

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$slug = $this->argument('slug');
		$name = $this->argument('name');

		$this->call('make:resource', ['name' => $name, '--module' => $slug]);
	}

	/**
	 * The array of command arguments.
	 *
	 * @return array
	 */
	public function getArguments()
	{
		return [['slug', InputArgument::REQUIRED, 'The name of the module', null], ['name', InputArgument::REQUIRED, 'The name of model for which the controller is being generated.', null],];
	}
}