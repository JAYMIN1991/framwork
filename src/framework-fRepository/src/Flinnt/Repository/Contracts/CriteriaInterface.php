<?php
namespace Flinnt\Repository\Contracts;

/**
 * Interface CriteriaInterface
 * @package Flinnt\Repository\Contracts
 */
interface CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository);
}
