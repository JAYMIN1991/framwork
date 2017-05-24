<?php
namespace Flinnt\Repository\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Flinnt\Repository\Generators\FileAlreadyExistsException;


use Flinnt\Repository\Generators\RepositoryGenerator;
use Flinnt\Repository\Generators\RepositoryInterfaceGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class RepositoryCommand
 *
 * @package Flinnt\Repository\Generators\Commands
 */
class RepositoryCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:repository';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new repository.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

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
	    if($this->option('module') != "")
	    {
		    config( [
			    'repository.generator.basePath' => config('modules.path',app_path('Modules'))."/".(ucfirst($this->option('module'))),
			    'repository.generator.rootNamespace' => 'App\\Modules\\'.(ucfirst($this->option('module'))).'\\'
		    ]);
	    }


    	$this->generators = new Collection();

       /* $this->generators->push(new MigrationGenerator([
            'name'   => 'create_' . snake_case(str_plural($this->argument('name'))) . '_table',
            'fields' => $this->option('fillable'),
            'force'  => $this->option('force'),
	        'module'=>$this->option('module'),
        ])); */
        $this->info("skipping migration generation for repository!");

        /*$modelGenerator = new ModelGenerator([
            'name'     => $this->argument('name'),
            'fillable' => $this->option('fillable'),
            'force'    => $this->option('force'),
	        'module'   => $this->option('module'),
        ]);
        $this->generators->push($modelGenerator);
		*/
        $this->info("skipping model generation for repository!");

        $this->generators->push(new RepositoryInterfaceGenerator([
            'name'  => $this->argument('name'),
            'force' => $this->option('force'),
	        'module'=> $this->option('module'),
        ]));

        foreach ($this->generators as $generator) {
            $generator->run();
        }
		$model = $this->ask("Please Provide Table Constant: ","TABLE_NAME_CONTSTANT");

	   // $this->info("You Entered: ".$model);


       /* $model = $modelGenerator->getRootNamespace() . '\\' . $modelGenerator->getName();
        $model = str_replace([
            "\\",
            '/'
        ], '\\', $model);*/

        try {
            (new RepositoryGenerator([
                'name'      => $this->argument('name'),
                'rules'     => $this->option('rules'),
                'validator' => $this->option('validator'),
                'force'     => $this->option('force'),
                'model'     => $model,
	            'module'    => $this->option('module'),
            ]))->run();
            $this->info("Repository created successfully.");
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
                'fillable',
                null,
                InputOption::VALUE_OPTIONAL,
                'The fillable attributes.',
                null
            ],
            [
                'rules',
                null,
                InputOption::VALUE_OPTIONAL,
                'The rules of validation attributes.',
                null
            ],
            [
                'validator',
                null,
                InputOption::VALUE_OPTIONAL,
                'Adds validator reference to the repository.',
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
