<?php
namespace Flinnt\Repository\Presenter;

use Exception;
use Flinnt\Repository\Transformer\ModelTransformer;

/**
 * Class ModelFractalPresenter
 * @package Flinnt\Repository\Presenter
 */
class ModelFractalPresenter extends FractalPresenter
{

    /**
     * Transformer
     *
     * @return ModelTransformer
     * @throws Exception
     */
    public function getTransformer()
    {
        if (!class_exists('League\Fractal\Manager')) {
            throw new Exception("Package required. Please install: 'composer require league/fractal' (0.12.*)");
        }

        return new ModelTransformer();
    }
}
