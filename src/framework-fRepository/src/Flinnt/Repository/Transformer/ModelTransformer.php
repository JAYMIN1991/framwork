<?php namespace Flinnt\Repository\Transformer;

use League\Fractal\TransformerAbstract;
use Flinnt\Repository\Contracts\Transformable;

/**
 * Class ModelTransformer
 * @package Flinnt\Repository\Transformer
 */
class ModelTransformer extends TransformerAbstract
{
	/**
	 * @param \Flinnt\Repository\Contracts\Transformable $model
	 * @return array
	 */
	public function transform(Transformable $model)
    {
        return $model->transform();
    }
}
