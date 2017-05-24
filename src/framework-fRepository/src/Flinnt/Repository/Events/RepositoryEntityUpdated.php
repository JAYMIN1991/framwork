<?php
namespace Flinnt\Repository\Events;

/**
 * Class RepositoryEntityUpdated
 * @package Flinnt\Repository\Events
 */
class RepositoryEntityUpdated extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "updated";
}
