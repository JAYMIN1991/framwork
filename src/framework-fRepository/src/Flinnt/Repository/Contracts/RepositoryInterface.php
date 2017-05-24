<?php
namespace Flinnt\Repository\Contracts;


use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Interface RepositoryInterface
 * @package Flinnt\Repository\Contracts
 */
interface RepositoryInterface
{
/*
    /**
     * Retrieve data array for populate field select
     *
     * @param string      $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection|array
     *<<<back_slash>>>
    public function lists($column, $key = null);

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return mixed
     *<<<back_slash>>>
    public function all($columns = ['*']);

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null  $limit
     * @param array $columns
     *
     * @return mixed
     *<<<back_slash>>>
    public function paginate($limit = null, $columns = ['*']);

    /**
     * Retrieve all data of repository, simple paginated
     *
     * @param null  $limit
     * @param array $columns
     *
     * @return mixed
     *<<<back_slash>>>
    public function simplePaginate($limit = null, $columns = ['*']);

    /**
     * Find data by id
     *
     * @param       $id
     * @param array $columns
     *
     * @return mixed
     *<<<back_slash>>>
    public function find($id, $columns = ['*']);

    /**
     * Find data by field and value
     *
     * @param       $field
     * @param       $value
     * @param array $columns
     *
     * @return mixed
     *<<<back_slash>>>
    public function findByField($field, $value, $columns = ['*']);

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     *<<<back_slash>>>
    public function findWhere(array $where, $columns = ['*']);

    /**
     * Find data by multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     *<<<back_slash>>>
    public function findWhereIn($field, array $values, $columns = ['*']);

    /**
     * Find data by excluding multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     *<<<back_slash>>>
    public function findWhereNotIn($field, array $values, $columns = ['*']);

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return mixed
     *<<<back_slash>>>
    public function create(array $attributes);

    /**
     * Update a entity in repository by id
     *
     * @param array $attributes
     * @param       $id
     *
     * @return mixed
     *<<<back_slash>>>
    public function update(array $attributes, $id);

    /**
     * Update or Create an entity in repository
     *
     * @throws ValidatorException
     *
     * @param array $attributes
     * @param array $values
     *
     * @return mixed
     *<<<back_slash>>>
    public function updateOrCreate(array $attributes, array $values = []);

    /**
     * Delete a entity in repository by id
     *
     * @param $id
     *
     * @return int
     *<<<back_slash>>>
    public function delete($id);

	/**
	 * Delete multiple entities by given criteria.
	 *
	 * @param array $where
	 *
	 * @return int
	 *<<<back_slash>>>
	public function deleteWhere(array $where);

    /**
     * Order collection by a given column
     *
     * @param string $column
     * @param string $direction
     *
     * @return $this
     *<<<back_slash>>>
    public function orderBy($column, $direction = 'asc');

    /**
     * Load relations
     *
     * @param $relations
     *
     * @return $this
     *<<<back_slash>>>
    public function with($relations);

    /**
     * Set hidden fields
     *
     * @param array $fields
     *
     * @return $this
     *<<<back_slash>>>
    public function hidden(array $fields);

    /**
     * Set visible fields
     *
     * @param array $fields
     *
     * @return $this
     *<<<back_slash>>>
    public function visible(array $fields);

    /**
     * Query Scope
     *
     * @param \Closure $scope
     *
     * @return $this
     *<<<back_slash>>>
    public function scopeQuery(\Closure $scope);

    /**
     * Reset Query Scope
     *
     * @return $this
     *<<<back_slash>>>
    public function resetScope();

    /**
     * Get Searchable Fields
     *
     * @return array
     *<<<back_slash>>>
    public function getFieldsSearchable();

    /**
     * Set Presenter
     *
     * @param $presenter
     *
     * @return mixed
     *<<<back_slash>>>
    public function setPresenter($presenter);

    /**
     * Skip Presenter Wrapper
     *
     * @param bool $status
     *
     * @return $this
     *<<<back_slash>>>
    public function skipPresenter($status = true);

    */
}
