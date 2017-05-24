<?php
namespace Flinnt\Repository\Eloquent;

use App\Common\GeneralHelpers;
use Closure;
use Flinnt\Repository\Exceptions\RecordNotFoundException;
use Flinnt\Repository\Traits\BuilderProviderTrait;
use DB;
use Exception;
use Flinnt\Core\Cache\CacheManager;
use Flinnt\Repository\Contracts\CriteriaInterface;
use Flinnt\Repository\Contracts\Presentable;
use Flinnt\Repository\Contracts\PresenterInterface;
use Flinnt\Repository\Contracts\RepositoryCriteriaInterface;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Events\RepositoryEntityCreated;
use Flinnt\Repository\Events\RepositoryEntityDeleted;
use Flinnt\Repository\Events\RepositoryEntityUpdated;
use Flinnt\Repository\Exceptions\RepositoryException;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class BaseRepository
 * @package Flinnt\Repository\Eloquent
 */
abstract class BaseRepository implements RepositoryInterface, RepositoryCriteriaInterface {

	use BuilderProviderTrait;
	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @var CacheManager
	 */
	protected $cache;

	/**
	 * as per Fluent
	 * @var String
	 */
	protected $primaryKey = 'id';

	/**
	 * @var array
	 */
	protected $fieldSearchable = [];

	/**
	 * @var PresenterInterface
	 */
	protected $presenter;

	/**
	 * @var ValidatorInterface
	 */
	protected $validator;

	/**
	 * Validation Rules
	 *
	 * @var array
	 */
	protected $rules = null;

	/**
	 * Collection of Criteria
	 *
	 * @var Collection
	 */
	protected $criteria;

	/**
	 * @var bool
	 */
	protected $skipCriteria = false;

	/**
	 * @var bool
	 */
	protected $skipPresenter = false;

	/**
	 * @var \Closure
	 */
	protected $scopeQuery = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->app = Application::getInstance();
		$this->criteria = new Collection();
		$this->cache = new CacheManager();
		$this->makeModel();
		$this->makePresenter();
		$this->makeValidator();
		$this->boot();
	}

	/**
	 * Fluent Model Query Builder.
	 *
	 * @return Builder
	 * @throws RepositoryException
	 */
	protected function makeModel() {
		$model = DB::table($this->model());

		if ( ! $model instanceof Builder ) {
			throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Query\\Builder");
		}

		return $this->model = $model;
	}

	/**
	 * Specify Model class name
	 *
	 * @return string
	 */
	abstract protected function model();

	/**
	 * @param null $presenter
	 *
	 * @return PresenterInterface
	 * @throws RepositoryException
	 */
	public function makePresenter( $presenter = null ) {
		$presenter = ! is_null($presenter) ? $presenter : $this->presenter();

		if ( ! is_null($presenter) ) {
			$this->presenter = is_string($presenter) ? $this->app->make($presenter) : $presenter;

			if ( ! $this->presenter instanceof PresenterInterface ) {
				throw new RepositoryException("Class {$presenter} must be an instance of Flinnt\\Repository\\Contracts\\PresenterInterface");
			}

			return $this->presenter;
		}

		return null;
	}

	/**
	 * Specify Presenter class name
	 *
	 * @return string
	 */
	protected function presenter() {
		return null;
	}

	/**
	 * @param null $validator
	 *
	 * @return null|ValidatorInterface
	 * @throws RepositoryException
	 */
	public function makeValidator( $validator = null ) {
		$validator = ! is_null($validator) ? $validator : $this->validator();

		if ( ! is_null($validator) ) {
			$this->validator = is_string($validator) ? $this->app->make($validator) : $validator;

			if ( ! $this->validator instanceof ValidatorInterface ) {
				throw new RepositoryException("Class {$validator} must be an instance of Prettus\\Validator\\Contracts\\ValidatorInterface");
			}

			return $this->validator;
		}

		return null;
	}

	/**
	 * Specify Validator class name of Flinnt\Validator\Contracts\ValidatorInterface
	 *
	 * @return null
	 * @throws Exception
	 */
	protected function validator() {

		if ( isset($this->rules) && ! is_null($this->rules) && is_array($this->rules) && ! empty($this->rules) ) {
			if ( class_exists('Prettus\Validator\LaravelValidator') ) {
				$validator = app('Prettus\Validator\LaravelValidator');
				if ( $validator instanceof ValidatorInterface ) {
					$validator->setRules($this->rules);

					return $validator;
				}
			} else {
				/* vishal: class is not owerriden only comment is overriden. This can lead to misguid.*/
				throw new Exception(trans('repository::packages.Flinnt_laravel_validation_required'));
			}
		}

		return null;
	}

	/**
	 *
	 */
	abstract protected function boot();

	/**
	 * Eloquent model creator
	 *
	 * @return Model
	 * @throws RepositoryException
	 */
	/*public function makeModel()
	{
		$model = $this->app->make($this->model());

		if (!$model instanceof Model) {
			throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
		}

		return $this->model = $model;
	}*/

	/**
	 * Set Presenter
	 *
	 * @param $presenter
	 *
	 * @return $this
	 */
	public function setPresenter( $presenter ) {
		$this->makePresenter($presenter);

		return $this;
	}

	/**
	 * Get Searchable Fields
	 *
	 * @return array
	 */
	public function getFieldsSearchable() {
		return $this->fieldSearchable;
	}

	/**
	 * Load relation with closure
	 *  Pankit : This is method of model. So it will not work with fluent
	 *
	 * @param string  $relation
	 * @param Closure $closure
	 *
	 * @return $this
	 */
	/*function whereHas( $relation, $closure )
	{
		$this->model = $this->model->whereHas($relation, $closure);

		return $this;
	}*/

	/**
	 * Push Criteria for filter the query
	 *
	 * @param $criteria
	 *
	 * @return $this
	 * @throws \Flinnt\Repository\Exceptions\RepositoryException
	 */
	public function pushCriteria( $criteria ) {
		if ( is_string($criteria) ) {
			$criteria = new $criteria;
		}
		if ( ! $criteria instanceof CriteriaInterface ) {
			throw new RepositoryException("Class " . get_class($criteria) . " must be an instance of Flinnt\\Repository\\Contracts\\CriteriaInterface");
		}
		$this->criteria->push($criteria);

		return $this;
	}

	/**
	 * Pop Criteria
	 *
	 * @param $criteria
	 *
	 * @return $this
	 */
	public function popCriteria( $criteria ) {
		$this->criteria = $this->criteria->reject(function ( $item ) use ( $criteria ) {
			if ( is_object($item) && is_string($criteria) ) {
				return get_class($item) === $criteria;
			}

			if ( is_string($item) && is_object($criteria) ) {
				return $item === get_class($criteria);
			}

			return get_class($item) === get_class($criteria);
		});

		return $this;
	}

	/**
	 * Find data by Criteria
	 *
	 * @param CriteriaInterface $criteria
	 *
	 * @return mixed
	 */
	public function getByCriteria( CriteriaInterface $criteria ) {
		$this->model = $criteria->apply($this->model, $this);
		$results = $this->model->get();
		$this->resetModel();

		return $this->parserResult($results);
	}

//  First method exists in builder trait
//    /**
//     * Retrieve first data of repository
//     *
//     * @param array $columns
//     *
//     * @return mixed
//     */
//    protected function first($columns = ['*'])
//    {
//        $this->applyCriteria();
//        $this->applyScope();
//
//        $results = $this->model->first($columns);
//
//        $this->resetModel();
//
//        return $this->parserResult($results);
//    }

	/**
	 * @throws RepositoryException
	 */
	protected function resetModel() {
		$this->makeModel();
	}

	/**
	 * Wrapper result data
	 *
	 * @param mixed $result
	 *
	 * @return mixed
	 */
	protected function parserResult( $result ) {

		/* check if skipPresenter is not true and the result is not empty and presenter is also set */
		if ( (! $this->skipPresenter) && (! empty($result)) && $this->presenter instanceof PresenterInterface ) {

			/* if result is collection or paginate then iterate it */
			if ( $result instanceof Collection || $result instanceof LengthAwarePaginator ) {

				foreach ( $result as $key => $value ) {
					$value = $this->presenter->present($value);
					$result[$key] = $value['data'];
				}

				return $result;
			} elseif ( $result instanceof Presentable ) {

				$result = $result->setPresenter($this->presenter);
			}
			/* result is other than collection or paginate */
			return $this->presenter->present($result);
		}

		/* Return result without modifying*/
		return $result;
	}

	/**
	 * Retrieve data array for populate field select
	 *
	 * @param string      $column
	 * @param string|null $key
	 *
	 * @return \Illuminate\Support\Collection|array
	 */
	protected function lists( $column, $key = null ) {
		return $this->pluck($column, $key);
	}

	/**
	 * Retrieve all data of repository, simple paginated. This method is overriding buildertrait's simplePaginate method
	 *
	 * @param null  $limit
	 * @param array $columns
	 *
	 * @return mixed
	 */
	/*protected function simplePaginate( $limit = null, $columns = ['*'] )
	{
		return $this->paginate($limit, $columns, "simplePaginate");
	}*/

	/**
	 * Retrieve all data of repository, paginated. This method is overriding buildertrait's paginate method
	 *
	 * @param int|null $limit
	 * @param int|null $page
	 * @param array    $columns
	 * @param string   $method
	 *
	 * @return mixed
	 */
	/*protected function paginate( $limit = null, $page = null, $columns = ['*'], $method = "paginate" )
	{
//		$this->applyCriteria();
//		$this->applyScope();
//		$limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
//		$results = $this->model->{$method}($limit, $columns, 'page', $page);
//		$results->appends(app('request')->query());
//		$this->resetModel();
		$results = $this->paginate($limit, $page, $columns, $method);

		return $this->parserResult($results);
	}*/

	/**
	 * Retrieve all data of repository
	 *
	 * @param array $columns
	 *
	 * @return mixed
	 */
	protected function all( $columns = ['*'] ) {
		$results = $this->get($columns);

		return $this->parserResult($results);
	}

	/**
	 * Find data by field and value
	 *
	 * @param       $field
	 * @param       $value
	 * @param array $columns
	 *
	 * @return mixed
	 */
	protected function findByField( $field, $value = null, $columns = ['*'] ) {
		$model = $this->where($field, '=', $value)->get($columns);

		return $this->parserResult($model);
	}

	/**
	 * Find data by multiple fields
	 *
	 * @param array $where
	 * @param array $columns
	 *
	 * @return mixed
	 */
	protected function findWhere( array $where, $columns = ['*'] ) {

		$this->applyConditions($where);
		$model = $this->get($columns);

		return $this->parserResult($model);
	}

	/**
	 * Applies the given where conditions to the model.
	 *
	 * @param array $where
	 *
	 * @return void
	 */
	protected function applyConditions( array $where ) {
		foreach ( $where as $field => $value ) {
			if ( is_array($value) ) {
				list($field, $condition, $val) = $value;
				$this->model = $this->model->where($field, $condition, $val);
			} else {
				$this->model = $this->model->where($field, '=', $value);
			}
		}
	}

	/**
	 * Find data by multiple values in one field
	 *
	 * @param       $field
	 * @param array $values
	 * @param array $columns
	 *
	 * @return mixed
	 */
	protected function findWhereIn( $field, array $values, $columns = ['*'] ) {
		$model = $this->whereIn($field, $values)->get($columns);

		return $this->parserResult($model);
	}

//   Comment it to use builder's delete method
//    /**
//     * Delete a entity in repository by id
//     *
//     * @param $id
//     *
//     * @return int
//     */
//    protected function delete($id)
//    {
//        $this->applyScope();
//
//        $temporarySkipPresenter = $this->skipPresenter;
//        $this->skipPresenter(true);
//
//        // as per eloquent
//	    $model = $this->find($id);
//        $originalModel = clone $model;
//
//        $this->skipPresenter($temporarySkipPresenter);
//        $this->resetModel();
//
//	    // as per eloquent
//	    // $deleted = $model->delete();
//
//	    // as per Fluent
//	     $deleted = $this->model->where($this->primaryKey,'=',$id)->delete();
//
//	    // as per the eloquent
//	    // event(new RepositoryEntityDeleted($this, $originalModel));
//
//	    // as per Fluent
//        event(new RepositoryEntityDeleted($this, $originalModel));
//
//        return $deleted;
//    }

	/**
	 * Find data by excluding multiple values in one field
	 *
	 * @param       $field
	 * @param array $values
	 * @param array $columns
	 *
	 * @return mixed
	 */
	protected function findWhereNotIn( $field, array $values, $columns = ['*'] ) {
		$model = $this->whereNotIn($field, $values)->get($columns);

		return $this->parserResult($model);
	}

	/**
	 * Save a new entity in repository
	 *
	 * @throws ValidatorException
	 *
	 * @param array $attributes
	 *
	 * @return mixed
	 */
	protected function create( array $attributes ) {
		if ( ! is_null($this->validator) ) {
			// we should pass data that has been casts by the model
			// to make sure data type are same because validator may need to use
			// this data to compare with data that fetch from database.
//            $attributes = $this->model->newInstance()->forceFill($attributes)->toArray();

			$this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
		}

		// as per eloquent model
		/*$model = $this->model->newInstance($attributes);
		$model->save();*/

		// as per Fluent
		$id = $this->insertGetId($attributes);

		// as per Fluent
		$model = $this->find($id);

		$this->resetModel();

		event(new RepositoryEntityCreated($this, $model));

		return $this->parserResult($model);
	}

	/**
	 * Find data by id
	 *
	 * @param       $id
	 * @param array $columns
	 *
	 * @return mixed
	 * @throws \Flinnt\Repository\Exceptions\RecordNotFoundException
	 */
	protected function find( $id, $columns = ['*'] ) {
		// as per eloquent model
		// $model = $this->model->findOrFail($id, $columns);
		$model = $this->where($this->primaryKey, "=", $id)->first($columns);

		if ( ! count($model) ) {
			// as per eloquent model
			// throw (new ModelNotFoundException)->setModel(get_class($this->model()), $id);

			// as per fluent
			throw (new RecordNotFoundException($this->model(), $id));
		}

		return $this->parserResult($model);
	}

	/**
	 * Update a entity in repository by id
	 *
	 *
	 * @param array $attributes
	 * @param       $id
	 *
	 * @return mixed
	 * @throws \Flinnt\Repository\Exceptions\RecordNotFoundException
	 */
	protected function updateById( array $attributes, $id ) {
		$this->applyScope();

		if ( ! is_null($this->validator) ) {
			// we should pass data that has been casts by the model
			// to make sure data type are same because validator may need to use
			// this data to compare with data that fetch from database.
//            $attributes = $this->model->newInstance()->forceFill($attributes)->toArray();

			$this->validator->with($attributes)->setId($id)->passesOrFail(ValidatorInterface::RULE_UPDATE);
		}

		$temporarySkipPresenter = $this->skipPresenter;

		$this->skipPresenter(true);

		// as per eloquent
		/*$model = $this->model->findOrFail($id);
		$model->fill($attributes);
		$model->save();*/

		// as per Fluent
		$this->where($this->primaryKey, "=", $id);
		$bool = $this->model->update($attributes);


		// as per Fluent
		if ( ! is_numeric($bool) ) {    //TODO:: Imporove code for is_numeric
			//var_dump($bool);
			throw (new RecordNotFoundException($this->model(), $id));
		}

		// as per Fluent
		$model = $this->skipCriteria()->find($id);

		$this->skipPresenter($temporarySkipPresenter);
		$this->resetModel();

		event(new RepositoryEntityUpdated($this, $model));

		return $this->parserResult($model);
	}

	/**
	 * Update the record
	 *
	 * @param array $data Array of data
	 *
	 * @return int Return the number of affected rows
	 */
	protected function update( array $data ) {
		$this->applyScope();

		$temporarySkipPresenter = $this->skipPresenter;
		$this->skipPresenter(true);

		$bool = $this->model->update($data);

		$this->skipPresenter($temporarySkipPresenter);
		$this->resetModel();

		return $bool;
	}

	/**
	 * Apply scope in current Query
	 *
	 * @return $this
	 */
	protected function applyScope() {
		if ( isset($this->scopeQuery) && is_callable($this->scopeQuery) ) {
			$callback = $this->scopeQuery;
			$this->model = $callback($this->model);
		}

		return $this;
	}
// Already Exists in parent
//	/**
//	 * @param string $column
//	 * @param string $direction
//	 * @return $this
//	 */
//	protected function orderBy($column, $direction = 'asc')
//    {
//        $this->model = $this->model->orderBy($column, $direction);
//
//        return $this;
//    }
//

	/**
	 * Skip Presenter Wrapper
	 *
	 * @param bool $status
	 *
	 * @return $this
	 */
	protected function skipPresenter( $status = true ) {
		$this->skipPresenter = $status;

		return $this;
	}

	/**
	 * Update or Create an entity in repository. Not sure, This method will work.
	 *
	 * @throws ValidatorException
	 *
	 * @param array $attributes
	 * @param array $values
	 *
	 * @return mixed
	 */
	protected function updateOrCreate( array $attributes, array $values = [] ) {
		$this->applyScope();

		if ( ! is_null($this->validator) ) {
			$this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_UPDATE);
		}

		$temporarySkipPresenter = $this->skipPresenter;

		$this->skipPresenter(true);

		// as per eloquent
		// $model = $this->model->updateOrCreate($attributes, $values);


		/*
		 * As per Fluent STARTS
		 */
		$model = null;
		$id = null;

		$model = $this->where($attributes)->first();

		if ( ! count($model) ) {

			if ( ! is_null($this->validator) ) {
				$this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
			}

			$id = $this->model->insertGetId(array_merge($attributes, $values));
		} else {
			$id = $model[$this->primaryKey];
			$this->model->where($this->primaryKey, $id)->update(array_merge($attributes, $values));
		}

		$this->resetModel();

		$model = $this->find($id);
		/*
		 * As per Fluent ENDS
		 */

		$this->skipPresenter($temporarySkipPresenter);
		$this->resetModel();

		event(new RepositoryEntityUpdated($this, $model));

		return $this->parserResult($model);
	}

	/**
	 * Delete multiple entities by given criteria.
	 *
	 * @param array $where
	 *
	 * @return int
	 */
	protected function deleteWhere( array $where ) {
		$temporarySkipPresenter = $this->skipPresenter;
		$this->skipPresenter(true);

		$this->applyConditions($where);
		$originalModel = $this->getAndPreserveCriteria();
		$deleted = $this->delete();

		// as per the eloquent
		// event(new RepositoryEntityDeleted($this, $this->model));

		//as per Fluent
		event(new RepositoryEntityDeleted($this, $originalModel));

		$this->skipPresenter($temporarySkipPresenter);

		return $deleted;
	}

	/**
	 * Check if entity has relation
	 *
	 * Pankit: As per model
	 *
	 * @param string $relation
	 *
	 * @return $this
	 */
	/*protected function has( $relation )
	{
		$this->model = $this->model->has($relation);

		return $this;
	}*/

	/**
	 * Load relations
	 *
	 * Pankit: as per model
	 *
	 * @param array|string $relations
	 *
	 * @return $this
	 */
	/*protected function with( $relations )
	{
		$this->model = $this->model->with($relations);

		return $this;
	}*/

	/**
	 * Set hidden fields
	 *
	 * Pankit: as per model
	 *
	 * @param array $fields
	 *
	 * @return $this
	 */
	/*protected function hidden( array $fields )
	{
		$this->model->setHidden($fields);

		return $this;
	}*/

	/**
	 * Set visible fields
	 *
	 * Pankit: as per model
	 *
	 * @param array $fields
	 *
	 * @return $this
	 */
	/*protected function visible( array $fields )
	{
		$this->model->setVisible($fields);

		return $this;
	}*/

	/**
	 * Skip Criteria
	 *
	 * @param bool $status
	 *
	 * @return $this
	 */
	protected function skipCriteria( $status = true ) {
		$this->skipCriteria = $status;

		return $this;
	}

	/**
	 * Apply criteria in current Query
	 *
	 * @return $this
	 */
	protected function applyCriteria() {
		if ( $this->criteriaApplied === false ) {
			if ( $this->skipCriteria === true ) {
				return $this;
			}

			$criteria = $this->getCriteria();

			if ( $criteria ) {
				foreach ( $criteria as $c ) {
					if ( $c instanceof CriteriaInterface ) {
						$this->model = $c->apply($this->model, $this);
					}
				}
			}

			$this->criteriaApplied = true;
		}

		return $this;
	}

	/**
	 * Get Collection of Criteria
	 *
	 * @return Collection
	 */
	public function getCriteria() {
		return $this->criteria;
	}

	/**
	 * Reset Model and Scope
	 */
	protected function resetModelAndCriteria() {
		$this->resetModel();
		$this->resetScope();
		$this->resetCriteria();
		$this->makePresenter();
		$this->boot();
	}

	/**
	 * Reset Query Scope
	 *
	 * @return $this
	 */
	protected function resetScope() {
		$this->scopeQuery = null;

		return $this;
	}

	/**
	 * Reset all Criterias
	 *
	 * @return $this
	 */
	protected function resetCriteria() {
		$this->criteria = new Collection();
		$this->criteriaApplied = false;

		return $this;
	}

	/**
	 * Convert the attributes to match tableName.attributeName syntax
	 *
	 * @param string $tableName  Name of the table
	 * @param array  $attributes Array of attributes
	 *
	 * @return array Array having value as tableName.attributeName
	 */
	protected function addTableToAttrib( $tableName, array $attributes ) {
		return array_map(function ( $attribute ) use ( $tableName ) {
			return $tableName . "." . $attribute;
		}, $attributes);
	}

	/**
	 * Append the table name to attribute name in where clause.
	 *
	 * @param string $tableName Name of the table
	 * @param array  $where     Array of where conditions
	 */
	protected function addTableToWhere( $tableName, array &$where ) {

		foreach ( $where as $field => $value ) {
			if ( is_array($value) ) {
				$value[0] = $tableName . "." . $value[0];
			} else {
				$newField = $tableName . "." . $field;
				$where[$newField] = $value;
				unset($where[$field]);
			}
		}
	}

	/**
	 * Query Scope
	 *
	 * @param \Closure $scope
	 *
	 * @return $this
	 */
	private function scopeQuery( \Closure $scope ) {
		$this->scopeQuery = $scope;

		return $this;
	}

}