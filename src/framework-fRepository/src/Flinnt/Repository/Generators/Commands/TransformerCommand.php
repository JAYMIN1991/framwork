<?php
namespace Flinnt\Repository\Generators\Commands;

use Illuminate\Console\Command;

use Flinnt\Repository\Generators\FileAlreadyExistsException;
use Flinnt\Repository\Generators\TransformerGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class TransformerCommand
 *
 * @package Flinnt\Repository\Generators\Commands
 */
class TransformerCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:transformer';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new transformer.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Transformer';

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
            (new TransformerGenerator([
                'name'  =>  $this->argument('name'),
                'force' => $this->option('force'),
	            'module'=> $this->option('module'),
            ]))->run();
            $this->info("Transformer created successfully.");
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
                'The name of model for which the transformer is being generated.',
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
