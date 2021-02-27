<?php

namespace MohammadMehrabani\RepositoryGenerator;

use InvalidArgumentException;

abstract class Repository implements RepositoryInterface
{
    /**
     * The model being used by repository.
     *
     * @var
     */
    public $model;

    /**
     * The relationships that should be eager loaded.
     *
     * @var array
     */
    public $relations = [];

    /**
     * Basic where clauses to affect query.
     *
     * @var array
     */
    protected $wheres = [];

    /**
     * The columns being selected.
     *
     * @var
     */
    protected $columns;

    /**
     * All of the available clause operators.
     *
     * @var array
     */
    public $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=',
        'like', 'like binary', 'not like', 'between', 'ilike',
        '&', '|', '^', '<<', '>>',
        'rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

    /**
     * Create a new repository instance.
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Set the columns to be selected.
     *
     * @param  array|mixed $columns
     * @return $this
     */
    public function select($columns = ['*'])
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();

        return $this;
    }

    /**
     * Set the query to get only active records from database.
     *
     * @return $this
     */
    public function active()
    {
        $this->where(config('repository-generator.active_column'), 1);

        return $this;
    }

    /**
     * Add an basic where clause to the query.
     *
     * @param $column
     * @param string $operator
     * @param mixed $value
     * @param string $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        list($value, $operator) = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() == 2
        );

        if ($this->invalidOperator($operator)) {
            list($value, $operator) = [$operator, '='];
        }

        $this->wheres[] = compact(
             'column', 'operator', 'value', 'boolean'
        );

        return $this;
    }

    /**
     * Add an "where in" clause to the query.
     *
     * @param  string $column
     * @param  mixed $values
     * @param  string $boolean
     * @param  bool $not
     * @return $this
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        $type = $not ? 'NotIn' : 'In';
        $this->wheres[] = compact('type', 'column', 'values', 'boolean');

        return $this;
    }

    /**
     * Add an "or where" clause to the query.
     *
     * @param  \Closure|string $column
     * @param  string $operator
     * @param  mixed $value
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function orWhere($column, $operator = null, $value = null)
    {
        return $this->where($column, $operator, $value, 'or');
    }

    /**
     * Set the relationships that should be eager loaded.
     *
     * @param  mixed $relations
     * @return $this
     */
    public function with($relations)
    {
        $relations = is_string($relations) ? func_get_args() : $relations;
        $this->relations[] = $relations;

        return $this;
    }

    /**
     * Retrieve the "count" result of the query.
     *
     * @return int
     */
    public function count()
    {
        $this->columns = [$this->model->getKeyName()];
        $query = $this->prepareQuery();

        return $query->count();
    }

    /**
     * Execute a query for a single record by ID.
     *
     * @param  int $id
     * @param  array $columns
     * @return mixed|static
     */
    public function find($id, $columns = ['*'])
    {
        return $this->where($this->model->getKeyName(), '=', $id)->first($columns);
    }

    /**
     * Execute a query for a single record by bindings.
     *
     * @param array $columns
     * @return mixed
     */
    public function first($columns = ['*'])
    {
        if (is_null($this->columns)) {
            $this->columns = $columns;
        }

        return $this->prepareQuery()->first();
    }

    /**
     * Get a single column's value from the first result of a query.
     *
     * @param  string  $column
     * @return mixed
     */
    public function value($column)
    {
        if ($result = $this->first([$column])) {
            return $result->{$column};
        }
    }

    /**
     * Add an "order by" clause to the query.
     *
     * @param $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->model = $this->model->orderBy($column, $direction);

        return $this;
    }

    /**
     * Execute the query.
     *
     * @param  array $columns
     * @return \Illuminate\Support\Collection
     */
    public function get($columns = ['*'])
    {
        if (is_null($this->columns)) {
            $this->columns = $columns;
        }

        return $this->prepareQuery()->get();
    }

    /**
     * Paginate the given query into a simple paginator.
     *
     * @param int $rows
     * @return mixed
     */
    public function paginate($rows = 20)
    {
        return $this->prepareQuery()->paginate($rows);
    }

    /**
     * Create a new database record.
     *
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Update database record with given attributes by ID.
     *
     * @param $id
     * @param $attributes
     * @return mixed
     */
    public function update($id, $attributes)
    {
        $this->columns = null;
        $object = $this->where($this->model->getKeyName(), $id)->first();
        $object->update($attributes);
        return $object;
    }

    /**
     * Delete mass or single record from the database.
     *
     * @param  mixed  $id
     * @return int
     */
    public function delete($id = null)
    {
        if (! is_null($id) && empty($this->wheres)) {
            $this->where($this->model->getKeyName(), $id);
        }

        return $this->prepareQuery()->delete();
    }

    /**
     * Delete a single record from the database by ID.
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Prepare a executable query.
     *
     * @return mixed
     */
    protected function prepareQuery()
    {
        $query = $this->model->select($this->columns);

        $wheres = $this->wheres;
        if (! is_null($wheres)) {
            foreach ($wheres as $where) {
                if (isset($where['values']) && isset($where['type'])) {
                    $query->whereIn($where['column'], $where['values']);
                } else {
                    $query->where($where['column'], $where['operator'], $where['value'], $where['boolean']);
                }
            }
        }

        $relations = $this->relations;
        if (! is_null($this->relations)) {
            foreach ($relations as $relation) {
                $query->with($relation);
            }
        }

        return $query;
    }

    /**
     * Prepare the value and operator for a where clause.
     *
     * @param  string $value
     * @param  string $operator
     * @param  bool $useDefault
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function prepareValueAndOperator($value, $operator, $useDefault = false)
    {
        if ($useDefault) {
            return [$operator, '='];
        } elseif ($this->invalidOperatorAndValue($operator, $value)) {
            throw new InvalidArgumentException('Illegal operator and value combination.');
        }

        return [$value, $operator];
    }

    /**
     * Determine if the given operator and value combination is legal.
     *
     * Prevents using Null values with invalid operators.
     *
     * @param  string $operator
     * @param  mixed $value
     * @return bool
     */
    protected function invalidOperatorAndValue($operator, $value)
    {
        return is_null($value) && in_array($operator, $this->operators) &&
            ! in_array($operator, ['=', '<>', '!=']);
    }

    /**
     * Determine if the given operator is supported.
     *
     * @param  string $operator
     * @return bool
     */
    protected function invalidOperator($operator)
    {
        return ! in_array(strtolower($operator), $this->operators, true);
    }
}
