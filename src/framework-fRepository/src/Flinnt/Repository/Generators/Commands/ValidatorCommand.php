<?php
namespace Flinnt\Repository\Generators\Commands;

use Illuminate\Console\Command;

use Flinnt\Repository\Generators\FileAlreadyExistsException;
use Flinnt\Repository\Generators\ValidatorGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ValidatorCommand
 *
 * @package Flinnt\Repository\Generators\Commands
 */
class ValidatorCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:validator';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new validator.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Validator';


    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
	    if($this->option('module') != "")
	    {
		    config( [
			    'repository.generator.basePath' => config('modules.path',app_path('Modules'))."/".(ucfirst($this->option('module'))),
			    'repository.generator.rootNamespace' => 'App\\Modules\\'.(ucfirst($this->option('module'))).'\\'
		    ]);
	    }

        try {
            (new ValidatorGenerator([
                'name'  => $this->argument('name'),
                'rules' => $this->option('rules'),
                'force' => $this->option('force'),
	            'module'=> $this->option('module'),
            ]))->run();
            $this->info("Validator created successfully.");
        } catch (FileAlreadyExistsException $e) {
            $this->error($this->type . ' already exists!');
        }
    }


    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of model for which the validator is being generated.',
                null
            ],
        ];
    }


    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            [
                'rules',
                null,
                InputOption::VALUE_OPTIONAL,
                'The rules of validation attributes.',
                null
            ],
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null
            ],
	        [
		        'module',
		        null,
		        InputOption::VALUE_OPTIONAL,
		        'Give module name to create within Module path.'
	        ]
        ];
    }
}
