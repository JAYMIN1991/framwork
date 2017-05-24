<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 28/12/16
 * Time: 7:33 PM
 */

namespace Flinnt\Repository\Criteria;


use Flinnt\Repository\Contracts\CriteriaInterface;
use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Class AbstractCriteria
 * @package Flinnt\Repository\Criteria
 */
abstract class AbstractCriteria implements CriteriaInterface
{

	/**
	 * Apply criteria in query repository
	 *
	 * @param                     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	abstract function apply( $model, RepositoryInterface $repository );

	/**
	 * Get the attribute name with table alias
	 * 
	 * @param string $tableName
	 * @param string $attributeName
	 *
	 * @return string
	 */
	protected function getAttributeName( $tableName, $attributeName)
	{
		return $tableName . "." . $attributeName;
	}
}