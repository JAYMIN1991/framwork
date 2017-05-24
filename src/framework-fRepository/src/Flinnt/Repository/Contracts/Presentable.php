<?php
namespace Flinnt\Repository\Contracts;

/**
 * Interface Presentable
 * @package Flinnt\Repository\Contracts
 */
interface Presentable
{
    /**
     * @param PresenterInterface $presenter
     *
     * @return mixed
     */
    public function setPresenter(PresenterInterface $presenter);

    /**
     * @return mixed
     */
    public function presenter();
}
