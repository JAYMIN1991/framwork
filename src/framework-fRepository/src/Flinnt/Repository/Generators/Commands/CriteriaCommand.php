<?php

namespace Flinnt\Repository\Generators\Commands;

use Illuminate\Console\Command;
use Flinnt\Repository\Generators\CriteriaGenerator;
use Flinnt\Repository\Generators\FileAlreadyExistsException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class CriteriaCommand
 *
 * @package Flinnt\Repository\Generators\Commands
 */
class CriteriaCommand extends Command
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:criteria';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new criteria.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Criteria';
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
            (new CriteriaGenerator([
                'name' => $this->argument('name'),
                'force' => $this->option('force'),
	            'module'=>$this->option('module'),
            ]))->run();

            $this->info("Criteria created successfully.");
        } catch (FileAlreadyExistsException $ex) {
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
                'The name of class being generated.',
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
