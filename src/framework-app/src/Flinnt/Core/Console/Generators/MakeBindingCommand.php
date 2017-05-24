<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 6/12/16
 * Time: 1:58 PM
 */

namespace Flinnt\Core\Console\Generators;


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class MakeBindingCommand
 *
 * @package Flinnt\Core\Console\Generators
 */
class MakeBindingCommand extends Command
{

	/**
	 * The name of command.
	 *
	 * @var string
	 */
	protected $name = 'make:module:bindings';

	/**
	 * The description of command.
	 *
	 * @var string
	 */
	protected $description = 'Add repository bindings to module\'s service provider.';

	/**
	 * The type of class being generated.
	 *
	 * @var string
	 */
	protected $type = 'Bindings';

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$slug = $this->argument('slug');
		$name = $this->argument('name');

		$this->call("make:bindings", ['name' => $name, "--module" => $slug]);
	}

	/**
	 * The array of command arguments.
	 *
	 * @return array
	 */
	public function getArguments()
	{
		return [['slug', InputArgument::REQUIRED, 'Slug of the module in which you want to generate bindings', null], ['name', InputArgument::REQUIRED, 'The name of the Abstract.', null]];
	}
}