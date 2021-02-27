<?php

namespace MohammadMehrabani\RepositoryGenerator;

interface RepositoryInterface
{
    /**
     * Bind columns to query.
     *
     * @param array $columns
     * @return mixed
     */
    public function select($columns = ['*']);

    /**
     * Bind where clause to query for active column.
     *
     * @return mixed
     */
    public function active();

    /**
     * Bind where clause to query.
     *
     * @param $column
     * @param null $operator
     * @param null $value
     * @param string $boolean
     * @return mixed
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and');

    /**
     * Bind where in clause to query.
     *
     * @param $column
     * @param $values
     * @param string $boolean
     * @param bool $not
     * @return mixed
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false);

    /**
     * Bind or where clause to query.
     *
     * @param $column
     * @param null $operator
     * @param null $value
     * @return mixed
     */
    public function orWhere($column, $operator = null, $value = null);

    /**
     * Set relationships for eager loading.
     *
     * @param $relations
     * @return mixed
     */
    public function with($relations);

    /**
     * Get count result from the database.
     *
     * @return mixed
     */
    public function count();

    /**
     * Get a single record by ID.
     *
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*']);

    /**
     * Get a single record by bindings.
     *
     * @param array $columns
     * @return mixed
     */
    public function first($columns = ['*']);

    /**
     * Get a single column's value.
     *
     * @param $column
     * @return mixed
     */
    public function value($column);

    /**
     * Bind order by clause to query.
     *
     * @param $column
     * @param string $direction
     * @return mixed
     */
    public function orderBy($column, $direction = 'asc');

    /**
     * Get all records by bindings.
     *
     * @param $columns
     * @return mixed
     */
    public function get($columns = ['*']);

    /**
     * Paginate the records.
     * @param $rows
     * @return mixed
     */
    public function paginate($rows = 20);

    /**
     * Create a new record.
     *
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes);

    /**
     * Update a record with given attributes by ID.
     *
     * @param $id
     * @param $attributes
     * @return mixed
     */
    public function update($id, $attributes);

    /**
     * Delete mass or a single record from the database.
     *
     * @param null $id
     * @return mixed
     */
    public function delete($id = null);

    /**
     * Delete a single record by ID.
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id);
}
