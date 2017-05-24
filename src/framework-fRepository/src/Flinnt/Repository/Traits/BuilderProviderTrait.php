<?php

namespace Flinnt\Repository\Traits;

use Closure;
use Illuminate\Database\Query\Builder;
use RuntimeException;


/**
 * Class BuilderProviderTrait
 * @package App\Common
 */
trait BuilderProviderTrait {

	/**
	 * @var Builder
	 */
	protected $model;

	/**
	 * @var bool
	 */
	protected $criteriaApplied = false;

	/**
	 * Get the SQL representation of the query.
	 *
	 * @return string
	 */
	public function toSql() {
		$this->applyCriteria();

		$query = $this->model->toSql();
		$connection = $this->getConnection();
		$pdo = $connection->getPdo();
		/*
			This method currently doesn't prepare parameter for utf-8 not support Binary Input. This need enhancement.
		*/
		//$bindings = $pdo->prepareBindings($this->getBindings());
		$bindings = $this->model->getBindings();

		if ( ! empty($bindings) ) {
			foreach ( $bindings as $key => $binding ) {
				// This regex matches placeholders only, not the question marks,
				// nested in quotes, while we iterate through the bindings
				// and substitute placeholders by suitable values.
				$regex = is_numeric($key) ? "/\?(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/" : "/:{$key}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";
				$query = preg_replace($regex, $pdo->quote($binding), $query, 1);
			}
		}

		return $query;
		/*
		$result = $this->model->toSql();
		$this->resetModelAndCriteria();

		return $result;*/
	}

	/**
	 * Handle dynamic method calls into the method.
	 *
	 * @param  string $method
	 * @param  array  $parameters
	 *
	 * @return mixed
	 *
	 * @throws \BadMethodCallException
	 */
	public function __call( $method, $parameters ) {
		if ( ! in_array($method, get_class_methods($this)) ) {
			return $this->model->$method($parameters);
		}

		return null;
	}

	/**
	 * Add an or where between statement to the query.
	 *
	 * @param  string $column
	 * @param  array  $values
	 *
	 * @return $this
	 */
	protected function orWhereBetween( $column, array $values ) {
		$this->model->orWhereBetween($column, $values);

		return $this;
	}

	/**
	 * Add a where not between statement to the query.
	 *
	 * @param  string $column
	 * @param  array  $values
	 * @param  string $boolean
	 *
	 * @return $this
	 */
	protected function whereNotBetween( $column, array $values, $boolean = 'and' ) {
		$this->model->whereNotBetween($column, $values, $boolean);

		return $this;
	}

	/**
	 * Add an or where not between statement to the query.
	 *
	 * @param  string $column
	 * @param  array  $values
	 *
	 * @return $this
	 */
	protected function orWhereNotBetween( $column, array $values ) {
		$this->model->orWhereNotBetween($column, $values);

		return $this;
	}

	/**
	 * Add a nested where statement to the query.
	 *
	 * @param  Closure $callback
	 * @param  string  $boolean
	 *
	 * @return $this
	 */
	protected function whereNested( Closure $callback, $boolean = 'and' ) {
		$this->model->whereNested($callback, $boolean);

		return $this;
	}

	/**
	 * Create a new query instance for nested where condition.
	 *
	 * @return \Illuminate\Database\Query\Builder
	 */
	protected function forNestedWhere() {
		return $this->model->forNestedWhere();
	}

	/**
	 * Add another query builder as a nested where to the query builder.
	 *
	 * @param  \Illuminate\Database\Query\Builder|static $query
	 * @param  string                                    $boolean
	 *
	 * @return $this
	 */
	protected function addNestedWhereQuery( $query, $boolean = 'and' ) {
		$this->model->addNestedWhereQuery($query, $boolean);

		return $this;
	}

	/**
	 * Add an exists clause to the query.
	 *
	 * @param  Closure $callback
	 * @param  string  $boolean
	 * @param  bool    $not
	 *
	 * @return $this
	 */
	protected function whereExists( Closure $callback, $boolean = 'and', $not = false ) {
		$this->model->whereExists($callback, $boolean, $not);

		return $this;
	}

	/**
	 * Add an or exists clause to the query.
	 *
	 * @param  Closure $callback
	 * @param  bool    $not
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function orWhereExists( Closure $callback, $not = false ) {
		$this->model->orWhereExists($callback, $not);

		return $this;
	}

	/**
	 * Add a where not exists clause to the query.
	 *
	 * @param  Closure $callback
	 * @param  string  $boolean
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function whereNotExists( Closure $callback, $boolean = 'and' ) {
		$this->model->whereNotExists($callback, $boolean);

		return $this;
	}

	/**
	 * Add a where not exists clause to the query.
	 *
	 * @param  Closure $callback
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function orWhereNotExists( Closure $callback ) {
		$this->model->orWhereNotExists($callback);

		return $this;
	}

	/**
	 * Add an exists clause to the query.
	 *
	 * @param  \Illuminate\Database\Query\Builder $query
	 * @param  string                             $boolean
	 * @param  bool                               $not
	 *
	 * @return $this
	 */
	protected function addWhereExistsQuery( Builder $query, $boolean = 'and', $not = false ) {
		$this->model->addWhereExistsQuery($query, $boolean, $not);

		return $this;
	}

	/**
	 * Add a "where in" clause to the query.
	 *
	 * @param  string $column
	 * @param  mixed  $values
	 * @param  string $boolean
	 * @param  bool   $not
	 *
	 * @return $this
	 */
	protected function whereIn( $column, $values, $boolean = 'and', $not = false ) {
		$this->model->whereIn($column, $values, $boolean, $not);

		return $this;
	}

	/**
	 * Add an "or where in" clause to the query.
	 *
	 * @param  string $column
	 * @param  mixed  $values
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function orWhereIn( $column, $values ) {
		$this->model->orWhereIn($column, $values);

		return $this;
	}

	/**
	 * Add a "where not in" clause to the query.
	 *
	 * @param  string $column
	 * @param  mixed  $values
	 * @param  string $boolean
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function whereNotIn( $column, $values, $boolean = 'and' ) {
		$this->model->whereNotIn($column, $values, $boolean);

		return $this;
	}

	/**
	 * Add an "or where not in" clause to the query.
	 *
	 * @param  string $column
	 * @param  mixed  $values
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function orWhereNotIn( $column, $values ) {
		$this->model->orWhereNotIn($column, $values);

		return $this;
	}

	/**
	 * Add a "where null" clause to the query.
	 *
	 * @param  string $column
	 * @param  string $boolean
	 * @param  bool   $not
	 *
	 * @return $this
	 */
	protected function whereNull( $column, $boolean = 'and', $not = false ) {
		$this->model->whereNull($column, $boolean, $not);

		return $this;
	}

	/**
	 * Add an "or where null" clause to the query.
	 *
	 * @param  string $column
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function orWhereNull( $column ) {
		$this->model->orWhereNull($column);

		return $this;
	}

	/**
	 * Add a "where not null" clause to the query.
	 *
	 * @param  string $column
	 * @param  string $boolean
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function whereNotNull( $column, $boolean = 'and' ) {
		$this->model->whereNotNull($column, $boolean);

		return $this;
	}

	/**
	 * Add an "or where not null" clause to the query.
	 *
	 * @param  string $column
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function orWhereNotNull( $column ) {
		$this->model->orWhereNotNull($column);

		return $this;
	}

	/**
	 * Add a "where date" statement to the query.
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed  $value
	 * @param  string $boolean
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function whereDate( $column, $operator, $value = null, $boolean = 'and' ) {
		$this->model->whereDate($column, $operator, $value, $boolean);

		return $this;
	}

	/**
	 * Add an "or where date" statement to the query.
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  string $value
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function orWhereDate( $column, $operator, $value ) {
		$this->model->orWhereDate($column, $operator, $value);

		return $this;
	}

	/**
	 * Add a "where time" statement to the query.
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  int    $value
	 * @param  string $boolean
	 *
	 * @return $this
	 */
	protected function whereTime( $column, $operator, $value, $boolean = 'and' ) {
		$this->model->whereTime($column, $operator, $value, $boolean);

		return $this;
	}

	/**
	 * Add an "or where time" statement to the query.
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  int    $value
	 *
	 * @return $this
	 */
	protected function orWhereTime( $column, $operator, $value ) {
		$this->model->orWhereTime($column, $operator, $value);

		return $this;
	}

	/**
	 * Add a "where day" statement to the query.
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed  $value
	 * @param  string $boolean
	 *
	 * @return $this
	 */
	protected function whereDay( $column, $operator, $value = null, $boolean = 'and' ) {
		$this->model->whereDay($column, $operator, $value, $boolean);

		return $this;
	}

	/**
	 * Add a "where month" statement to the query.
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed  $value
	 * @param  string $boolean
	 *
	 * @return $this
	 */
	protected function whereMonth( $column, $operator, $value = null, $boolean = 'and' ) {
		$this->model->whereMonth($column, $operator, $value, $boolean);

		return $this;
	}

	/**
	 * Add a "where year" statement to the query.
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed  $value
	 * @param  string $boolean
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function whereYear( $column, $operator, $value = null, $boolean = 'and' ) {
		$this->model->whereYear($column, $operator, $value, $boolean);

		return $this;
	}

	/**
	 * Handles dynamic "where" clauses to the query.
	 *
	 * @param  string $method
	 * @param  string $parameters
	 *
	 * @return $this
	 */
	protected function dynamicWhere( $method, $parameters ) {
		$this->model->dynamicWhere($method, $parameters);

		return $this;
	}

	/**
	 * Add a "group by" clause to the query.
	 *
	 * @param  array ...$groups
	 *
	 * @return $this
	 */
	protected function groupBy( ...$groups ) {
		$this->model->groupBy($groups);

		return $this;
	}

	/**
	 * Add a "having" clause to the query.
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  string $value
	 * @param  string $boolean
	 *
	 * @return $this
	 */
	protected function having( $column, $operator = null, $value = null, $boolean = 'and' ) {
		$this->model->having($column, $operator, $value, $boolean);

		return $this;
	}

	/**
	 * Add a "or having" clause to the query.
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  string $value
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function orHaving( $column, $operator = null, $value = null ) {
		$this->model->orHaving($column, $operator, $value);

		return $this;
	}

	/**
	 * Add a raw having clause to the query.
	 *
	 * @param  string $sql
	 * @param  array  $bindings
	 * @param  string $boolean
	 *
	 * @return $this
	 */
	protected function havingRaw( $sql, array $bindings = [], $boolean = 'and' ) {
		$this->model->havingRaw($sql, $bindings, $boolean);

		return $this;
	}

	/**
	 * Add a raw or having clause to the query.
	 *
	 * @param  string $sql
	 * @param  array  $bindings
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function orHavingRaw( $sql, array $bindings = [] ) {
		$this->model->orHavingRaw($sql, $bindings);

		return $this;
	}

	/**
	 * Add an "order by" clause to the query.
	 *
	 * @param  string $column
	 * @param  string $direction
	 *
	 * @return $this
	 */
	protected function orderBy( $column, $direction = 'asc' ) {
		$this->model->orderBy($column, $direction);

		return $this;
	}

	/**
	 * Add an "order by" clause for a timestamp to the query.
	 *
	 * @param  string $column
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function latest( $column = 'created_at' ) {
		$this->model->latest($column);

		return $this;
	}

	/**
	 * Add an "order by" clause for a timestamp to the query.
	 *
	 * @param  string $column
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function oldest( $column = 'created_at' ) {
		$this->model->oldest($column);

		return $this;
	}

	/**
	 * Put the query's results in random order.
	 *
	 * @param  string $seed
	 *
	 * @return $this
	 */
	protected function inRandomOrder( $seed = '' ) {
		$this->model->inRandomOrder($seed);

		return $this;
	}

	/**
	 * Add a raw "order by" clause to the query.
	 *
	 * @param  string $sql
	 * @param  array  $bindings
	 *
	 * @return $this
	 */
	protected function orderByRaw( $sql, $bindings = [] ) {
		$this->model->orderByRaw($sql, $bindings);

		return $this;
	}

	/**
	 * Set the "offset" value of the query.
	 *
	 * @param  int $value
	 *
	 * @return $this
	 */
	protected function offset( $value ) {
		$this->model->offset($value);

		return $this;
	}

	/**
	 * Alias to set the "offset" value of the query.
	 *
	 * @param  int $value
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function skip( $value ) {
		$this->model->skip($value);

		return $this;
	}

	/**
	 * Set the "limit" value of the query.
	 *
	 * @param  int $value
	 *
	 * @return $this
	 */
	protected function limit( $value ) {
		$this->model->limit($value);

		return $this;
	}

	/**
	 * Add a union all statement to the query.
	 *
	 * @param  \Illuminate\Database\Query\Builder|\Closure $query
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function unionAll( $query ) {
		return $this->union($query, true);
	}

	/**
	 * Add a union statement to the query.
	 *
	 * @param  \Illuminate\Database\Query\Builder|\Closure $query
	 * @param  bool                                        $all
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function union( $query, $all = false ) {
		$this->model->union($query, $all);

		return $this;
	}

	/**
	 * Lock the selected rows in the table for updating.
	 *
	 * @return $this
	 */
	protected function lockForUpdate() {
		return $this->lock(true);
	}

	/**
	 * Lock the selected rows in the table.
	 *
	 * @param  bool $value
	 *
	 * @return $this
	 */
	protected function lock( $value = true ) {
		$this->model->lock($value);

		return $this;
	}

	/**
	 * Share lock the selected rows in the table.
	 *
	 * @return $this
	 */
	protected function sharedLock() {
		return $this->lock(false);
	}

	/**
	 * Execute a query for a single record by ID.
	 *
	 * @param  int   $id
	 * @param  array $columns
	 *
	 * @return mixed
	 */
	protected function find( $id, $columns = ['*'] ) {
		$result = $this->where('id', '=', $id)->first($columns);

		return $result;
	}

	/**
	 * Execute the query and get the first result.
	 *
	 * @param  array $columns
	 *
	 * @return \stdClass|array|null
	 */
	protected function first( $columns = ['*'] ) {
		return $result = $this->take(1)->get($columns)->first();
	}

	/**
	 * Alias to set the "limit" value of the query.
	 *
	 * @param  int $value
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function take( $value ) {
		$this->model->take($value);

		return $this;
	}

	/**
	 * Add a basic where clause to the query.
	 *
	 * @param  string|array|\Closure $column
	 * @param  string                $operator
	 * @param  mixed                 $value
	 * @param  string                $boolean
	 *
	 * @return $this
	 */
	protected function where( $column, $operator = null, $value = null, $boolean = 'and' ) {
		$this->model->where($column, $operator, $value, $boolean);

		return $this;
	}

	/**
	 * Get a single column's value from the first result of a query.
	 *
	 * @param  string $column
	 *
	 * @return mixed
	 */
	protected function value( $column ) {
		$this->applyCriteria();
		$result = $this->model->value($column);
		$this->resetModelAndCriteria();

		return $this->parserResult($result);
	}

	/**
	 * Paginate the given query into a simple paginator.
	 *
	 * @param  int      $perPage
	 * @param  array    $columns
	 * @param  string   $pageName
	 * @param  int|null $page
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	protected function paginate( $perPage = null, $columns = ['*'], $pageName = 'page', $page = null ) {
		$this->applyCriteria();
		$limit = is_null($perPage) ? config('repository.pagination.limit', 15) : $perPage;
		$result = $this->model->paginate($limit, $columns, $pageName, $page);
		$this->resetModelAndCriteria();

		return $this->parserResult($result);
	}

	/**
	 * Get a paginator only supporting simple next and previous links.
	 *
	 * This is more efficient on larger data-sets, etc.
	 *
	 * @param  int      $perPage
	 * @param  array    $columns
	 * @param  string   $pageName
	 * @param  int|null $page
	 *
	 * @return \Illuminate\Contracts\Pagination\Paginator
	 */
	protected function simplePaginate( $perPage = 15, $columns = ['*'], $pageName = 'page', $page = null ) {
		$this->applyCriteria();
		$result = $this->model->simplePaginate($perPage, $columns, $pageName, $page);
		$this->resetModelAndCriteria();

		return $this->parserResult($result);
	}

	/**
	 * Get the count of the total records for the paginate. It seems this method will not execute ever. because its only used in paginate and we are directly calling paginate of model.
	 *
	 * Pankit:  If you are using simplePaginate and you want count of total entries then we can use this.
	 *
	 * @param  array $columns
	 *
	 * @return int
	 */
	protected function getCountForPagination( $columns = ['*'] ) {
		$this->applyCriteria();

		$result = $this->model->getCountForPagination($columns);
		$this->resetModelAndCriteria();

		return $result;
	}

	/**
	 * Get a generator for the given query.
	 *
	 * @return \Generator
	 */
	protected function cursor() {
		$this->applyCriteria();
		$result = $this->model->cursor();
		$this->resetModelAndCriteria();

		return $result;
	}

	/**
	 * Chunk the results of a query by comparing numeric IDs.
	 * Function is not returning anything
	 *
	 * @param  int      $count
	 * @param  callable $callback
	 * @param  string   $column
	 * @param  string   $alias
	 *
	 * @return bool
	 */
	protected function chunkById( $count, callable $callback, $column = 'id', $alias = null ) {
		$lastId = 0;

		$this->applyCriteria();

		do {
			$clone = clone $this;

			$results = $clone->forPageAfterId($count, $lastId, $column)->getPreserveCrit();

			$countResults = $results->count();

			if ( $countResults == 0 ) {
				break;
			}

			if ( call_user_func($callback, $results) === false ) {
				return false;
			}

			$lastId = $results->last()->{$column};  //TODO :: this function is not returning anything please check this.
		} while ( $countResults == $count );

		$this->resetModelAndCriteria();

		return true;
	}

	/**
	 * @param array $columns
	 *
	 * @return \Illuminate\Support\Collection
	 */
	private function getPreserveCrit( $columns = ['*'] ) {
		return $this->model->get($columns);
	}

	/**
	 * Constrain the query to the next "page" of results after a given ID.
	 *
	 * @param  int    $perPage
	 * @param  int    $lastId
	 * @param  string $column
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function forPageAfterId( $perPage = 15, $lastId = 0, $column = 'id' ) {
		$this->model->forPageAfterId($perPage, $lastId, $column);

		return $this;
	}

	/**
	 * Execute a callback over each item while chunking.
	 *
	 * @param  callable $callback
	 * @param  int      $count
	 *
	 * @return bool
	 *
	 * @throws \RuntimeException
	 */
	protected function each( callable $callback, $count = 1000 ) {
		if ( empty($this->model->orders) && empty($this->model->unionOrders) ) {
			throw new RuntimeException('You must specify an orderBy clause when using the "each" function.');
		}

		return $this->chunk($count, function ( $results ) use ( $callback ) {
			foreach ( $results as $key => $value ) {
				if ( $callback($value, $key) === false ) {
					return false;
				}
			}
		});
	}

	/**
	 * Chunk the results of the query.
	 *
	 * @param  int      $count
	 * @param  callable $callback
	 *
	 * @return bool
	 */
	protected function chunk( $count, callable $callback ) {
		$page = 1;
		$this->applyCriteria();
		do {
			$results = $this->forPage($page, $count)->getPreserveCrit();

			$countResults = $results->count();

			if ( $countResults == 0 ) {
				break;
			}

			// On each chunk result set, we will pass them to the callback and then let the
			// developer take care of everything within the callback, which allows us to
			// keep the memory low for spinning through large result sets for working.
			if ( call_user_func($callback, $results) === false ) {
				return false;
			}

			$page++;
		} while ( $countResults == $count );

		$this->resetModelAndCriteria();

		return true;
	}

	/**
	 * Set the limit and offset for a given page.
	 *
	 * @param  int $page
	 * @param  int $perPage
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function forPage( $page, $perPage = 15 ) {
		$this->model->forPage($page, $perPage);

		return $this;
	}

	/**
	 * Concatenate values of a given column as a string.
	 *
	 * @param  string $column
	 * @param  string $glue
	 *
	 * @return string
	 */
	protected function implode( $column, $glue = '' ) {
		return $this->pluck($column)->implode($glue);
	}

	/**
	 * Get an array with the values of a given column.
	 *
	 * @param  string      $column
	 * @param  string|null $key
	 *
	 * @return \Illuminate\Support\Collection
	 */
	protected function pluck( $column, $key = null ) {
		$results = $this->get(is_null($key) ? [$column] : [$column, $key]);

		// If the columns are qualified with a table or have an alias, we cannot use
		// those directly in the "pluck" operations since the results from the DB
		// are only keyed by the column itself. We'll strip the table out here.
		return $results->pluck($this->stripTableForPluck($column), $this->stripTableForPluck($key));
	}

	/**
	 * Execute the query as a "select" statement.
	 *
	 * @param array $columns
	 *
	 * @return \Illuminate\Support\Collection
	 */
	protected function get( $columns = ['*'] ) {
		$result = $this->getData($columns);
		$this->resetModelAndCriteria();

		return $result;
	}

	/**
	 * Execute the query as a "select" statement and preserve the model and criteria
	 *
	 * @param array $columns
	 *
	 * @return \Illuminate\Support\Collection
	 */
	protected function getAndPreserveCriteria( $columns = ['*'] ) {
		return $this->getData($columns);
	}

	/**
	 * Execute the query as a "select" statement.
	 *
	 * @param array $columns
	 *
	 * @return \Illuminate\Support\Collection
	 */
	private function getData( $columns = ['*'] ) {
		$this->applyCriteria();

		return $this->model->get($columns);
	}

	/**
	 * Strip off the table name or alias from a column identifier.
	 *
	 * @param string $column
	 *
	 * @return string|null
	 */
	protected function stripTableForPluck( $column ) {
		return is_null($column) ? $column : last(preg_split('~\.| ~', $column));
	}

	/**
	 * Determine if any rows exist for the current query.
	 *
	 * @return bool
	 */
	protected function exists() {
		$this->applyCriteria();
		$result = $this->model->exists();
		$this->resetModelAndCriteria();

		return $result;
	}

	/**
	 * Retrieve the "count" result of the query.
	 *
	 * @param  string $columns
	 *
	 * @return int
	 */
	protected function count( $columns = '*' ) {
		if ( ! is_array($columns) ) {
			$columns = [$columns];
		}

		return $this->aggregate(__FUNCTION__, $columns);
	}

	/**
	 * Execute an aggregate function on the database.
	 *
	 * @param  string $function
	 * @param  array  $columns
	 *
	 * @return mixed
	 */
	protected function aggregate( $function, $columns = ['*'] ) {
		$this->applyCriteria();
		$result = $this->model->aggregate($function, $columns);
		$this->resetModelAndCriteria();

		return $result;
	}

	/**
	 * Retrieve the minimum value of a given column.
	 *
	 * @param  string $column
	 *
	 * @return mixed
	 */
	protected function min( $column ) {
		return $this->aggregate(__FUNCTION__, [$column]);
	}

	/**
	 * Retrieve the maximum value of a given column.
	 *
	 * @param  string $column
	 *
	 * @return mixed
	 */
	protected function max( $column ) {
		return $this->aggregate(__FUNCTION__, [$column]);
	}

	/**
	 * Retrieve the sum of the values of a given column.
	 *
	 * @param  string $column
	 *
	 * @return mixed
	 */
	protected function sum( $column ) {
		$result = $this->aggregate(__FUNCTION__, [$column]);

		return $result ? : 0;
	}

	/**
	 * Alias for the "avg" method.
	 *
	 * @param  string $column
	 *
	 * @return mixed
	 */
	protected function average( $column ) {
		return $this->avg($column);
	}

	/**
	 * Retrieve the average of the values of a given column.
	 *
	 * @param  string $column
	 *
	 * @return mixed
	 */
	protected function avg( $column ) {
		return $this->aggregate(__FUNCTION__, [$column]);
	}

	/**
	 * Execute a numeric aggregate function on the database.
	 *
	 * @param  string $function
	 * @param  array  $columns
	 *
	 * @return float|int
	 */
	protected function numericAggregate( $function, $columns = ['*'] ) {
		$result = $this->aggregate($function, $columns);

		if ( ! $result ) {
			return 0;
		}

		if ( is_int($result) || is_float($result) ) {
			return $result;
		}

		if ( strpos((string) $result, '.') === false ) {
			return (int) $result;
		}

		return (float) $result;
	}

	/**
	 * Insert a new record into the database.
	 *
	 * @param  array $values
	 *
	 * @return bool
	 */
	protected function insert( array $values ) {
		return $this->model->insert($values);
	}

	/**
	 * Insert a new record and get the value of the primary key.
	 *
	 * @param  array  $values
	 * @param  string $sequence
	 *
	 * @return int
	 */
	protected function insertGetId( array $values, $sequence = null ) {
		return $this->model->insertGetId($values, $sequence);
	}

	/**
	 * Update a record in the database.
	 *
	 * @param  array $values
	 *
	 * @return int
	 */
	protected function updateById( array $values ) {
		return $this->model->update($values);
	}

	/**
	 * Increment a column's value by a given amount.
	 *
	 * @param  string $column
	 * @param  int    $amount
	 * @param  array  $extra
	 *
	 * @return int
	 */
	protected function increment( $column, $amount = 1, array $extra = [] ) {
		return $this->model->increment($column, $amount, $extra);
	}

	/**
	 * Decrement a column's value by a given amount.
	 *
	 * @param  string $column
	 * @param  int    $amount
	 * @param  array  $extra
	 *
	 * @return int
	 */
	protected function decrement( $column, $amount = 1, array $extra = [] ) {
		return $this->model->decrement($column, $amount, $extra);
	}

	/**
	 * Delete a record from the database.
	 *
	 * @param  mixed $id
	 *
	 * @return int
	 */
	protected function delete( $id = null ) {
		return $this->model->delete($id);
	}

	/**
	 * Run a truncate statement on the table.
	 *
	 * @return void
	 */
	protected function truncate() {
		$this->model->truncate();
	}

	/**
	 * Get a new instance of the query builder.
	 *
	 * @return \Illuminate\Database\Query\Builder
	 */
	protected function newQuery() {
		return $this->model->newQuery();
	}

	/**
	 * Merge an array of where clauses and bindings.
	 *
	 * @param  array $wheres
	 * @param  array $bindings
	 *
	 * @return void
	 */
	protected function mergeWheres( $wheres, $bindings ) {
		$this->model->mergeWheres($wheres, $bindings);
	}

	/**
	 * Create a raw database expression.
	 *
	 * @param  mixed $value
	 *
	 * @return \Illuminate\Database\Query\Expression
	 */
	protected function raw( $value ) {
		return $this->model->raw($value);
	}

	/**
	 * Get the current query value bindings in a flattened array.
	 *
	 * @return array
	 */
	protected function getBindings() {
		return $this->model->getBindings();
	}

	/**
	 * Get the raw array of bindings.
	 *
	 * @return array
	 */
	protected function getRawBindings() {
		return $this->model->getRawBindings();
	}

	/**
	 * Set the bindings on the query builder.
	 *
	 * @param  array  $bindings
	 * @param  string $type
	 *
	 * @return $this
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function setBindings( array $bindings, $type = 'where' ) {
		$this->model->setBindings($bindings, $type);

		return $this;
	}

	/**
	 * Add a binding to the query.
	 *
	 * @param  mixed  $value
	 * @param  string $type
	 *
	 * @return $this
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function addBinding( $value, $type = 'where' ) {
		$this->model->addBinding($value, $type);

		return $this;
	}

	/**
	 * Merge an array of bindings into our bindings.
	 *
	 * @param  \Illuminate\Database\Query\Builder $query
	 *
	 * @return $this
	 */
	protected function mergeBindings( Builder $query ) {
		$this->model->mergeBindings($query);

		return $this;
	}

	/**
	 * Get the database connection instance.
	 *
	 * @return \Illuminate\Database\ConnectionInterface
	 */
	protected function getConnection() {
		return $this->model->getConnection();
	}

	/**
	 * Get the database query processor instance.
	 *
	 * @return \Illuminate\Database\Query\Processors\Processor
	 */
	protected function getProcessor() {
		return $this->model->getProcessor();
	}

	/**
	 * Get the query grammar instance.
	 *
	 * @return \Illuminate\Database\Query\Grammars\Grammar
	 */
	protected function getGrammar() {
		return $this->model->getGrammar();
	}

	/**
	 * Use the write pdo for query.
	 *
	 * @return $this
	 */
	protected function useWritePdo() {
		$this->model->useWritePdo();

		return $this;
	}

	/**
	 * Check if criteria is applied or not
	 *
	 * @return bool
	 */
	protected function isCriteriaApplied() {
		return $this->criteriaApplied;
	}

	/**
	 * Set the columns to be selected.
	 *
	 * @param  array|mixed $columns
	 *
	 * @return $this
	 */
	protected function select( $columns = ['*'] ) {
		$this->model->select($columns);

		return $this;
	}

	/**
	 * Add a new "raw" select expression to the query.
	 *
	 * @param  string $expression
	 * @param  array  $bindings
	 *
	 * @return $this
	 */
	protected function selectRaw( $expression, array $bindings = [] ) {
		$this->model->selectRaw($expression, $bindings);

		return $this;
	}

	/**
	 * Add a subselect expression to the query.
	 *
	 * @param  \Closure|Builder|string $query
	 * @param  string                  $as
	 *
	 * @return $this
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function selectSub( $query, $as ) {
		$this->model->selectSub($query, $as);

		return $this;
	}

	/**
	 * Add a new select column to the query.
	 *
	 * @param  array|mixed $column
	 *
	 * @return $this
	 */
	protected function addSelect( $column ) {
		$this->model->addSelect($column);

		return $this;
	}

	/**
	 * Force the query to only return distinct results.
	 *
	 * @return $this
	 */
	protected function distinct() {
		$this->model->distinct();

		return $this;
	}

	/**
	 * Set the table which the query is targeting.
	 *
	 * @param  string $table
	 *
	 * @return $this
	 */
	protected function from( $table ) {
		$this->model->from($table);

		return $this;
	}

	/**
	 * Add a join clause to the query.
	 *
	 * @param  string $table
	 * @param  string $one
	 * @param  string $operator
	 * @param  string $two
	 * @param  string $type
	 * @param  bool   $where
	 *
	 * @return $this
	 */
	protected function join( $table, $one, $operator = null, $two = null, $type = 'inner', $where = false ) {
		$this->model->join($table, $one, $operator, $two, $type, $where);

		return $this;

	}

	/**
	 * Add a "join where" clause to the query.
	 *
	 * @param  string $table
	 * @param  string $one
	 * @param  string $operator
	 * @param  string $two
	 * @param  string $type
	 *
	 * @return $this;
	 */
	protected function joinWhere( $table, $one, $operator, $two, $type = 'inner' ) {
		$this->model->joinWhere($table, $one, $operator, $two, $type);

		return $this;
	}

	/**
	 * Add a left join to the query.
	 *
	 * @param  string $table
	 * @param  string $first
	 * @param  string $operator
	 * @param  string $second
	 *
	 * @return $this
	 */
	protected function leftJoin( $table, $first, $operator = null, $second = null ) {
		$this->model->leftJoin($table, $first, $operator, $second);

		return $this;
	}

	/**
	 * Add a "join where" clause to the query.
	 *
	 * @param  string $table
	 * @param  string $one
	 * @param  string $operator
	 * @param  string $two
	 *
	 * @return $this
	 */
	protected function leftJoinWhere( $table, $one, $operator, $two ) {
		$this->model->leftJoinWhere($table, $one, $operator, $two);

		return $this;

	}

	/**
	 * Add a right join to the query.
	 *
	 * @param  string $table
	 * @param  string $first
	 * @param  string $operator
	 * @param  string $second
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function rightJoin( $table, $first, $operator = null, $second = null ) {
		$this->model->rightJoin($table, $first, $operator, $second);

		return $this;
	}

	/**
	 * Add a "right join where" clause to the query.
	 *
	 * @param  string $table
	 * @param  string $one
	 * @param  string $operator
	 * @param  string $two
	 *
	 * @return $this
	 */
	protected function rightJoinWhere( $table, $one, $operator, $two ) {
		$this->model->rightJoinWhere($table, $one, $operator, $two);

		return $this;
	}

	/**
	 * Add a "cross join" clause to the query.
	 *
	 * @param  string $table
	 * @param  string $first
	 * @param  string $operator
	 * @param  string $second
	 *
	 * @return $this
	 */
	protected function crossJoin( $table, $first = null, $operator = null, $second = null ) {
		$this->model->crossJoin($table, $first, $operator, $second);

		return $this;
	}

	/**
	 * Apply the callback's query changes if the given "value" is true.
	 *
	 * @param  bool     $value
	 * @param  \Closure $callback
	 * @param  \Closure $default
	 *
	 * @return \Illuminate\Database\Query\Builder
	 */
	protected function when( $value, $callback, $default = null ) {
		$this->model->when($value, $callback, $default);

		return $this;
	}

	/**
	 * Add an "or where" clause to the query.
	 *
	 * @param  \Closure|string $column
	 * @param  string          $operator
	 * @param  mixed           $value
	 *
	 * @return $this
	 */
	protected function orWhere( $column, $operator = null, $value = null ) {
		//$this->applyCriteria();

		$this->model->orWhere($column, $operator, $value);

		return $this;
	}

	/**
	 * Add a "where" clause comparing two columns to the query.
	 *
	 * @param  string|array $first
	 * @param  string|null  $operator
	 * @param  string|null  $second
	 * @param  string|null  $boolean
	 *
	 * @return $this
	 */
	protected function whereColumn( $first, $operator = null, $second = null, $boolean = 'and' ) {
		$this->model->whereColumn($first, $operator, $second, $boolean);

		return $this;
	}

	/**
	 * Add an "or where" clause comparing two columns to the query.
	 *
	 * @param  string|array $first
	 * @param  string|null  $operator
	 * @param  string|null  $second
	 *
	 * @return $this
	 */
	protected function orWhereColumn( $first, $operator = null, $second = null ) {
		$this->model->orWhereColumn($first, $operator, $second);

		return $this;
	}

	/**
	 * Add a raw where clause to the query.
	 *
	 * @param  string $sql
	 * @param  mixed  $bindings
	 * @param  string $boolean
	 *
	 * @return $this
	 */
	protected function whereRaw( $sql, $bindings = [], $boolean = 'and' ) {
		$this->model->whereRaw($sql, $bindings, $boolean);

		return $this;

	}

	/**
	 * Add a raw or where clause to the query.
	 *
	 * @param  string $sql
	 * @param  array  $bindings
	 *
	 * @return \Illuminate\Database\Query\Builder|static
	 */
	protected function orWhereRaw( $sql, array $bindings = [] ) {
		$this->model->orWhereRaw($sql, $bindings);

		return $this;
	}

	/**
	 * Add a where between statement to the query.
	 *
	 * @param  string $column
	 * @param  array  $values
	 * @param  string $boolean
	 * @param  bool   $not
	 *
	 * @return $this
	 */
	protected function whereBetween( $column, array $values, $boolean = 'and', $not = false ) {
		$this->model->whereBetween($column, $values, $boolean, $not);

		return $this;

	}

//	/**
//	 * Execute the query as a "select" statement.
//	 *
//	 * @param  array  $columns
//	 * @return \Illuminate\Support\Collection
//	 */
//	public function get($columns = ['*'])
//	{
//		//$sql = $this->model->where("xyz", 1);
//		/*$builder = $this->model->newQuery()->from($this->model());
//		$builder = $this->applyCriteriaAndScopeOnBuilder($builder);
	//$this->model->newFromBuilder($record);
//		$this->model = $builder->merge($this->model);*/
//		$result =  $this->model->get($columns);
//		$this->resetModelAndCriteria();
//		return $result;
//
//	}

//	protected function applyCriteriaOnBuilder(Builder $builder)
//	{
//
//		if ($this->skipCriteria === true) {
//			return $this;
//		}
//
//		$criteria = $this->getCriteria();
//
//		if ($criteria) {
//			foreach ($criteria as $c) {
//				if ($c instanceof CriteriaInterface) {
//					$builder = $c->apply($builder, $this);
//				}
//			}
//		}
//
//		return $builder;
//	}
//
//	/**
//	 * Apply scope in current Query
//	 *
//	 * @return $this
//	 */
//	protected function applyScopeOnBuilder(Builder $builder)
//	{
//		if (isset($this->scopeQuery) && is_callable($this->scopeQuery)) {
//			$callback = $this->scopeQuery;
//			$builder = $callback($builder);
//		}
//
//		return $builder;
//	}
//
//	/**
//	 * Apply Criteria and Scope
//	 * @return  Builder
//	 */
//	protected function applyCriteriaAndScopeOnBuilder(Builder $builder)
//	{
//		$builder = $this->applyCriteriaOnBuilder($builder);
//		$builder = $this->applyScopeOnBuilder($builder);
//		return $builder;
//	}


}
