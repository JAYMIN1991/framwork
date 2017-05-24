<?php
namespace Flinnt\Repository\Generators\Commands;

use File;
use Illuminate\Console\Command;
use Flinnt\Repository\Generators\BindingsGenerator;
use Flinnt\Repository\Generators\FileAlreadyExistsException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class BindingsCommand
 *
 * @package Flinnt\Repository\Generators\Commands
 */
class BindingsCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:bindings';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Add repository bindings to service provider.';

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
	    //var_dump(\Module::where('slug', 'login'));
    	//var_dump(module_path('login'));
//	    var_dump(module_class('login','Providers\\ModuleServiceProvider'));

//    	return;
	    if($this->option('module') != "")
	    {
		    config( [
			    'repository.generator.basePath' => config('modules.path',app_path('Modules'))."/".(ucfirst($this->option('module'))),
			    'repository.generator.rootNamespace' => 'App\\Modules\\'.(ucfirst($this->option('module'))).'\\'
		    ]);
	    }

        try {
            $bindingGenerator = new BindingsGenerator([
                'name'   => $this->argument('name'),
                'force'  => $this->option('force'),
	            'module' => $this->option('module'),
            ]);


	        // generate repository service provider
	       // var_dump($bindingGenerator->getPath());
	       // var_dump($bindingGenerator->getConfigGeneratorClassPath($bindingGenerator->getPathConfigNode()));
	     //   return;
	        if (!file_exists($bindingGenerator->getPath())) {
		        $this->call('make:module:provider', [
                    'name' => $bindingGenerator->getConfigGeneratorClassPath($bindingGenerator->getPathConfigNode()),
			        'slug' => $this->option('module'),
                ]);
                // placeholder to mark the place in file where to prepend repository bindings
                $provider = File::get($bindingGenerator->getPath());
                File::put($bindingGenerator->getPath(), vsprintf(str_replace('//', '%s', $provider), [
                    '//',
                    $bindingGenerator->bindPlaceholder
                ]));
            }
            $bindingGenerator->run();
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
