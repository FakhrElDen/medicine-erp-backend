<?php

namespace App\Models;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;

/**
 * @method static ?static find($id, array|string $columns = ['*']) find a model by its primary key.
 * @method static static findOrFail($id, array|string $columns = ['*']) find a model by its primary key or throw and exception.
 * @method static ?static first(array|string $columns = ['*']) execute the query and get the first result.
 * @method static ?static firstWhere(\Closure|string|array|Expression $column, $operator = null, $value = null, string $boolean = 'and') Add a basic where clause to the query, and return the first result.
 * @method static static create(array $attributes = []) Save a new model and return the instance.
 * @method static static updateOrCreate(array $attributes, array $values = []) Create or update a record matching the attributes, and fill it with values.
 * @method static static firstOrCreate(array $attributes = [], array $values = []) Get the first record matching the attributes or create it.
 * @method static Builder select($columns = ['*']) Set the columns to be selected.
 * @method static Builder selectRaw(string $expression, array $bindings) Add a new "raw" select expression to the query.
 * @method static Builder whereRaw(string $sql, array $bindings, string $boolean = 'and') Add a raw where clause to the query.
 * @method static Builder where(\Closure|string|array|Expression $column, $operator = null, $value = null, string $boolean = 'and') Add a basic where clause to the query.
 * @method static Builder whereDate(\Closure|string|array|Expression $column, $operator = null, $value = null, string $boolean = 'and') Add a "where date" statement to the query.
 * @method static Builder whereIn(string|Expression $column, $values, $boolean = 'and', $not = false) Add a "where in" clause to the query.
 * @method static Builder when($value = null, callable $callback = null, callable $default = null) Apply the callback if the given "value" is (or resolves to) truthy.
 * @method static Builder join(string|Expression $table, \Closure|string $first, string $operator = null, string $second = null, string $type = 'inner', bool $where = false) Add a join clause to the query.
 * @method static Builder leftJoin(string|Expression $table, \Closure|string $first, string $operator = null, string $second = null) Add a left join to the query
 * @method static Builder latest(\Closure|QueryBuilder|Expression|string $column = 'created_at') Add an "order by" clause for a timestamp to the query.
 * @method static Builder whereHas(string $relation, \Closure $callback = null, string $operator = '>=', int $count = 1) Add a relationship count / exists condition to the query with where clauses.
 * @method static Builder whereRelation(string $relation, \Closure|string|array|Expression $column, $operator = null, $value = null) Add a basic where clause to a relationship query.
 * @method static Builder with(string|array $relations, string|\Closure $callback = null) Set the relationships that should be eager loaded.
 * @method static Collection|static[] get($columns = ['*']) Execute the query as a "select" statement.
 * @method static SupportCollection pluck(string|Expression $column, string $key = null) Get a collection with the values of a given column.
 */
class BaseModel extends Model
{
    public function scopeApplyFilters($query, array $input)
    {
        $filterClass = $this->filter ?? null;
        if ($filterClass != null) {
            foreach ($input as $key => $value) {
                $fields = $filterClass::$fields;
                if (array_key_exists($key, $fields)) {
                    $methodName = $fields[$key];
                    (new $filterClass)->$methodName($query, $value);
                }
            }
        }

        return $query;
    }

    public function scopeApplySorts($query, $input)
    {
        $sortClass = $this->sort ?? null;

        if ($sortClass != null) {
            if (isset($input->sort_by)) {
                $fields = $sortClass::$fields;
                if (array_key_exists($input->sort_by, $fields)) {
                    $methodName = $fields[$input->sort_by];
                    (new $sortClass)->$methodName($query, $input->direction ?? 'desc');
                }
            }
        }

        return $query;
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
