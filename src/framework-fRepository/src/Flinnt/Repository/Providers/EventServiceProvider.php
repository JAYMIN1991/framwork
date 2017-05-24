<?php
namespace Flinnt\Repository\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class EventServiceProvider
 *
 * @package Flinnt\Repository\Providers
 */
class EventServiceProvider extends ServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Flinnt\Repository\Events\RepositoryEntityCreated' => [
            'Flinnt\Repository\Listeners\CleanCacheRepository'
        ],
        'Flinnt\Repository\Events\RepositoryEntityUpdated' => [
            'Flinnt\Repository\Listeners\CleanCacheRepository'
        ],
        'Flinnt\Repository\Events\RepositoryEntityDeleted' => [
            'Flinnt\Repository\Listeners\CleanCacheRepository'
        ]
    ];

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function boot()
    {
        $events = app('events');

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        //
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        return $this->listen;
    }
}
