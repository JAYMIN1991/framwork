<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 5/12/16
 * Time: 7:22 PM
 */

namespace Flinnt\Core\Providers;


use Flinnt\Core\Console\Generators\MakeBindingCommand;
use Flinnt\Core\Console\Generators\MakeCommandGenerator;
use Flinnt\Core\Console\Generators\MakeCriteriaCommand;
use Flinnt\Core\Console\Generators\MakeEntityCommand;
use Flinnt\Core\Console\Generators\MakeMailableCommand;
use Flinnt\Core\Console\Generators\MakePresenterCommand;
use Flinnt\Core\Console\Generators\MakeRepositoryCommand;
use Flinnt\Core\Console\Generators\MakeResourceControllerCommand;
use Flinnt\Core\Console\Generators\MakeSMSNotifiableCommand;
use Flinnt\Core\Console\Generators\MakeTransformerCommand;
use Illuminate\Support\ServiceProvider;

/**
 * Class GeneratorCommandServiceProvider
 *
 * @package Flinnt\Core\Providers
 */
class GeneratorCommandServiceProvider extends ServiceProvider {

	/**
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * @var array
	 */
	protected $commands = [
		'Mail'               => 'flinnt.make.mail',
		'SMS'                => 'flinnt.make.sms',
		'Binding'            => 'make.module.bindings',
		'ResourceController' => 'make.module.resource',
		'Criteria'           => 'make.module.criteria',
		'Transformer'        => 'make.module.transformer',
		'Presenter'          => 'make.module.presenter',
		'Entity'             => 'make.module.entity',
		'Repository'         => 'make.module.repository',
		'CommandGenerator'   => 'flinnt.make.command',
	];

	/**
	 * Register the command with application
	 */
	public function register() {
		$this->registerCommands($this->commands);
	}

	/**
	 * Register the given commands.
	 *
	 * @param  array $commands
	 *
	 * @return void
	 */
	protected function registerCommands( array $commands ) {
		foreach ( array_keys($commands) as $command ) {
			$method = "register{$command}Command";

			call_user_func_array([$this, $method], []);
		}

		$this->commands(array_values($commands));
	}


	/**
	 * Register make mail command
	 */
	protected function registerMailCommand() {
		$this->app->singleton('flinnt.make.mail', function ( $app ) {
			return $app[MakeMailableCommand::class];
		});
	}

	/**
	 * Register make sms command
	 */
	protected function registerSMSCommand() {
		$this->app->singleton('flinnt.make.sms', function ( $app ) {
			return $app[MakeSMSNotifiableCommand::class];
		});
	}

	/**
	 * register module binding command
	 */
	protected function registerBindingCommand() {
		$this->app->singleton('make.module.bindings', function ( $app ) {
			return $app[MakeBindingCommand::class];
		});
	}

	/**
	 * Register make resource command
	 */
	protected function registerResourceControllerCommand() {
		$this->app->singleton('make.module.resource', function ( $app ) {
			return $app[MakeResourceControllerCommand::class];
		});
	}

	/**
	 * Register make module criteria command
	 */
	protected function registerCriteriaCommand() {
		$this->app->singleton('make.module.criteria', function ( $app ) {
			return $app[MakeCriteriaCommand::class];
		});
	}

	/**
	 * Register make module transformer command
	 */
	protected function registerTransformerCommand() {
		$this->app->singleton('make.module.transformer', function ( $app ) {
			return $app[MakeTransformerCommand::class];
		});
	}

	/**
	 * Register make module presenter command
	 */
	protected function registerPresenterCommand() {
		$this->app->singleton('make.module.presenter', function ( $app ) {
			return $app[MakePresenterCommand::class];
		});
	}

	/**
	 * Register make module entity command
	 */
	protected function registerEntityCommand() {
		$this->app->singleton('make.module.entity', function ( $app ) {
			return $app[MakeEntityCommand::class];
		});
	}

	/**
	 * Register make module repository command
	 */
	protected function registerRepositoryCommand() {
		$this->app->singleton('make.module.repository', function ( $app ) {
			return $app[MakeRepositoryCommand::class];
		});
	}

	/**
	 * Register make artisan command command
	 */
	protected function registerCommandGeneratorCommand() {
		$this->app->singleton('flinnt.make.command', function ( $app ) {
			return $app[MakeCommandGenerator::class];
		});
	}

//	/**
//	 * Get the services provided by the provider.
//	 *
//	 * @return array
//	 */
//	public function provides() {
//
//		return [
//			'flinnt.make.mail',
//			'flinnt.make.sms',
//			'make.module.bindings',
//			'make.module.resource',
//			'make.module.criteria',
//			'make.module.transformer',
//			'make.module.presenter',
//			'make.module.entity',
//			'make:module.repository'
//		];
//	}


}