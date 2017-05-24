<?php
namespace Flinnt\Repository\Contracts;

/**
 * Interface PresenterInterface
 * @package Flinnt\Repository\Contracts
 */
interface PresenterInterface
{
    /**
     * Prepare data to present
     *
     * @param $data
     *
     * @return mixed
     */
    public function present($data);
}
