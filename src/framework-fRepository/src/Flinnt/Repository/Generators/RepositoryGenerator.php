<?php
namespace Flinnt\Repository\Generators;

use Flinnt\Repository\Generators\Migrations\SchemaParser;

/**
 * Class RepositoryGenerator
 * @package Flinnt\Repository\Generators
 */
class RepositoryGenerator extends Generator
{

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'repository/eloquent';

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        return parent::getRootNamespace() . parent::getConfigGeneratorClassPath($this->getPathConfigNode());
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'repositories';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath() . '/' . parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true) . '/' . $this->getName() . '.php';
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return config('repository.generator.basePath', app_path());
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        $repository = parent::getRootNamespace() . parent::getConfigGeneratorClassPath('interfaces') . '\\' . $this->getName() . 'Repo;';
        $repository = str_replace([
            "\\",
            '/'
        ], '\\', $repository);


        return array_merge(parent::getReplacements(), [
            'fillable'      => $this->getFillable(),
            'use_validator' => $this->getValidatorUse(),
            'validator'     => $this->getValidatorMethod(),
            'repository'    => $repository,
            'model'         => isset($this->options['model']) ? $this->options['model'] : ''
        ]);
    }

    /**
     * Get the fillable attributes.
     *
     * @return string
     */
    public function getFillable()
    {
        if (!$this->fillable) {
            return '[]';
        }
        $results = '[' . PHP_EOL;

        foreach ($this->getSchemaParser()->toArray() as $column => $value) {
            $results .= "\t\t'{$column}'," . PHP_EOL;
        }

        return $results . "\t" . ']';
    }

    /**
     * Get schema parser.
     *
     * @return SchemaParser
     */
    public function getSchemaParser()
    {
        return new SchemaParser($this->fillable);
    }

	/**
	 * @return string
	 */
	public function getValidatorUse()
    {
        $validator = $this->getValidator();

        return "use {$validator};";
    }


	/**
	 * @return string
	 */
	public function getValidator()
    {
        $validatorGenerator = new ValidatorGenerator([
            'name'  => $this->name,
            'rules' => $this->rules,
            'force' => $this->force,
        ]);

        $validator = $validatorGenerator->getRootNamespace() . '\\' . $validatorGenerator->getName();

        return str_replace([
            "\\",
            '/'
        ], '\\', $validator) . 'Validator';

    }


	/**
	 * @return string
	 */
	public function getValidatorMethod()
    {
        if ($this->validator != 'yes') {
            return '';
        }

        $class = $this->getClass();

        return '/**' . PHP_EOL . '    * Specify Validator class name' . PHP_EOL . '    *' . PHP_EOL . '    * @return mixed' . PHP_EOL . '    */' . PHP_EOL . '    public function validator()' . PHP_EOL . '    {' . PHP_EOL . PHP_EOL . '        return ' . $class . 'Validator::class;' . PHP_EOL . '    }' . PHP_EOL;
    }
}
