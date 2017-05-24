<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 6/12/16
 * Time: 4:25 PM
 */

namespace Flinnt\Core\Console\Generators;


use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class MakeEntityCommand
 *
 * @package Flinnt\Core\Console\Generators
 */
class MakeEntityCommand extends Command
{

	/**
	 * The name of command.
	 *
	 * @var string
	 */
	protected $name = 'make:module:entity';

	/**
	 * The description of command.
	 *
	 * @var string
	 */
	protected $description = 'Create a new entity.';

	/**
	 * @var Collection
	 */
	protected $generators = null;

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$slug = $this->argument('slug');
		$name = $this->argument('name');

		$this->call('make:entity', ['name' => $name, '--module' => $slug]);
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