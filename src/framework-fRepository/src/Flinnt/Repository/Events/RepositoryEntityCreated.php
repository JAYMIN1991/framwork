<?php

namespace Flinnt\Repository\Events;

/**
 * Class RepositoryEntityCreated
 * @package Flinnt\Repository\Events
 */
class RepositoryEntityCreated extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "created";
}
