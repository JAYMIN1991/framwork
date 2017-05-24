<?php namespace Flinnt\Repository\Traits;

/**
 * Class TransformableTrait
 * @package Flinnt\Repository\Traits
 */
trait TransformableTrait
{

    /**
     * @return array
     */
    public function transform()
    {
        return $this->toArray();
    }
}
