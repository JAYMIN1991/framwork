<?php
namespace Flinnt\Repository\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package Flinnt\Repository\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
	        __DIR__ . '/../../../resources/config/repository.php' => config_path('repository.php')
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../../../resources/config/repository.php', 'repository');

        $this->loadTranslationsFrom(__DIR__ . '/../../../resources/lang', 'repository');
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands('Flinnt\Repository\Generators\Commands\RepositoryCommand');
        $this->commands('Flinnt\Repository\Generators\Commands\TransformerCommand');
        $this->commands('Flinnt\Repository\Generators\Commands\PresenterCommand');
        $this->commands('Flinnt\Repository\Generators\Commands\EntityCommand');
        $this->commands('Flinnt\Repository\Generators\Commands\ValidatorCommand');
        $this->commands('Flinnt\Repository\Generators\Commands\ControllerCommand');
        $this->commands('Flinnt\Repository\Generators\Commands\BindingsCommand');
        $this->commands('Flinnt\Repository\Generators\Commands\CriteriaCommand');
        $this->app->register('Flinnt\Repository\Providers\EventServiceProvider');
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [

        ];
    }
}
