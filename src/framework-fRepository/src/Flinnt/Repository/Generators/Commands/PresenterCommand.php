<?php
namespace Flinnt\Repository\Generators\Commands;

use Illuminate\Console\Command;
use Flinnt\Repository\Generators\FileAlreadyExistsException;
use Flinnt\Repository\Generators\PresenterGenerator;
use Flinnt\Repository\Generators\TransformerGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class PresenterCommand
 *
 * @package Flinnt\Repository\Generators\Commands
 */
class PresenterCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:presenter';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new presenter.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Presenter';


    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {

	    // $this->info(json_encode($this->option()));
	    //return ;

	    if($this->option('module') != "")
	    {
		    config( [
			    'repository.generator.basePath' => config('modules.path',app_path('Modules'))."/".(ucfirst($this->option('module'))),
			    'repository.generator.rootNamespace' => 'App\\Modules\\'.(ucfirst($this->option('module'))).'\\'
		    ]);
	    }


        try {
            (new PresenterGenerator([
                'name'  => $this->argument('name'),
                'force' => $this->option('force'),
	            'module'=> $this->option('module'),
	        ]))->run();
            $this->info("Presenter created successfully.");

            if (!\File::exists(app_path() . '/Transformers/' . $this->argument('name') . 'Transformer.php')) {
                if ($this->confirm('Would you like to create a Transformer? [y|N]')) {
                    (new TransformerGenerator([
                        'name'  => $this->argument('name'),
                        'force' => $this->option('force'),
	                    'module'=> $this->option('module'),
                    ]))->run();
                    $this->info("Transformer created successfully.");
                }
            }
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
                'The name of model for which the presenter is being generated.',
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
