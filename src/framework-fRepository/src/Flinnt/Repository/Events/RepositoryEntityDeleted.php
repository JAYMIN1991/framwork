<?php
namespace Flinnt\Repository\Events;

/**
 * Class RepositoryEntityDeleted
 * @package Flinnt\Repository\Events
 */
class RepositoryEntityDeleted extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "deleted";
}
