<?php
namespace Flinnt\Repository\Events;

use Illuminate\Database\Query\Builder as Model;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Class RepositoryEventBase
 * @package Flinnt\Repository\Events
 */
abstract class RepositoryEventBase
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var string
     */
    protected $action;

    /**
     * as per eloquent
     * @param RepositoryInterface $repository
     * @param Model               $model
     */
    /*public function __construct(RepositoryInterface $repository, Model $model)
    {
        $this->repository = $repository;
        $this->model = $model;
    }*/

	/**
	 * as per fluent
	 *
	 * @param RepositoryInterface $repository
	 * @param array|Collection  $model
	 */
	public function __construct(RepositoryInterface $repository, $model)
	{
		$this->repository = $repository;
		$this->model = $model;
	}

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
