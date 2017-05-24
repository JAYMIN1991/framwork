<?php
namespace Flinnt\Repository\Generators\Commands;

use Illuminate\Console\Command;

use Flinnt\Repository\Generators\ControllerGenerator;
use Flinnt\Repository\Generators\FileAlreadyExistsException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ControllerCommand
 *
 * @package Flinnt\Repository\Generators\Commands
 */
class ControllerCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:resource';

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
	    if($this->option('module') != "")
	    {
		    config( [
			    'repository.generator.basePath' => config('modules.path',app_path('Modules'))."/".(ucfirst($this->option('module'))),
			    'repository.generator.rootNamespace' => 'App\\Modules\\'.(ucfirst($this->option('module'))).'\\'
		    ]);
	    }

    	try {
		    if($this->option('module') != "")
		    {
			    // Generate create request for controller
			    $this->call('make:module:request', [
				    'name' => $this->argument('name') . 'CreateRequest',
				    'slug' => $this->option('module'),
			    ]);
			    // Generate update request for controller
			    $this->call('make:module:request', [
				    'name' => $this->argument('name') . 'UpdateRequest',
				    'slug' => $this->option('module'),
			    ]);
		    }
		    else
		    {
			    // Generate create request for controller
			    $this->call('make:request', [
				    'name' => $this->argument('name') . 'CreateRequest'
			    ]);
			    // Generate update request for controller
			    $this->call('make:request', [
				    'name' => $this->argument('name') . 'UpdateRequest'
			    ]);
		    }

		    //var_dump("not creating controller");
			//return;

            (new ControllerGenerator([
                'name' => $this->argument('name'),
                'force' => $this->option('force'),
	            'module'=>$this->option('module'),
            ]))->run();
            $this->info($this->type . ' created successfully.');
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
                'The name of model for which the controller is being generated.',
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
