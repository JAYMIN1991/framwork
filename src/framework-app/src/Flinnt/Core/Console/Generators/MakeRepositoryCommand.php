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
 * Class MakeRepositoryCommand
 *
 * @package Flinnt\Core\Console\Generators
 */
class MakeRepositoryCommand extends Command
{

	/**
	 * The name of command.
	 *
	 * @var string
	 */
	protected $name = 'make:module:repository';

	/**
	 * The description of command.
	 *
	 * @var string
	 */
	protected $description = 'Add repository within module.';

	/**
	 * The type of class being generated.
	 *
	 * @var string
	 */
	protected $type = 'Repository';

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$slug = $this->argument('slug');
		$name = $this->argument('name');

		$this->call("make:repository", ['name' => $name, "--module" => $slug]);
	}

	/**
	 * The array of command arguments.
	 *
	 * @return array
	 */
	public function getArguments()
	{
		return [['slug', InputArgument::REQUIRED, 'Slug of the module in which you want to generate Repository', null], ['name', InputArgument::REQUIRED, 'The name of Repository.', null]];
	}
}