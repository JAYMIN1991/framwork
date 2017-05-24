<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-8
 * Date: 22/11/16
 * Time: 7:31 PM
 */

namespace Flinnt\Repository\Criteria;


use Flinnt\Repository\Contracts\CriteriaInterface;
use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Class AbstractDBRequestCriteria
 *
 * @package Flinnt\Repository\Criteria
 */
abstract class AbstractDBRequestCriteria implements CriteriaInterface  {

	protected $config = [];

	protected $is_applicable = null;

	protected $filter_on = null;

	/**
	 * AbstractDBRequestCriteria constructor.
	 *
	 * @param array $config
	 * @param null $filter_on
	 * @param bool $is_applicable
	 * @throws \InvalidArgumentException
	 */
	public function __construct($config=[], $filter_on = null, $is_applicable = true) {

		if(!empty($config)) {

			if(!isset($config["alias"])) {
				throw new \InvalidArgumentException("Config must have alias");
			}

			if(!isset($config["field"])) {
				throw new \InvalidArgumentException("Config must have field");
			}

		}

		$this->filter_on = $filter_on;
		$this->config = $config;
		$this->is_applicable = $is_applicable;
	}


	/**
	 * Apply criteria in query repository
	 *
	 * @param                     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply($model, RepositoryInterface $repository) {
		if(!$this->isApplicable()) {
			return "";
		}

		// write code to send output
		//TODO: Write your code here

		return null;
	}

	/**
	 * @return bool|mixed
	 */
	public function isApplicable() {

		if(is_bool($this->is_applicable) && $this->is_applicable) {
			return true;
		}

		if(is_callable($this->is_applicable)) {
			return call_user_func($this->is_applicable, $this->filter_on, $this->config);
		} else {
			return true;
		}
	}

}