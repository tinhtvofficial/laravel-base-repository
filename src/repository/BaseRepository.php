<?php

namespace Luuka\LaravelBaseRepository\Repository;

use Exception;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Luuka\LaravelBaseRepository\Repository\Interfaces\BaseRepositoryInterface;

/**
 * Class BaseRepository.
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $app;

    protected $model;

    protected $defaultLimit = 20;

    protected $defaultSorts = "id:desc";

    protected $query;

    protected $with = [];

    abstract public function model();

    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be instance of Illuminate\\Database\\Eloquent\\Model");
        }

        $this->model = $model;

        return $this->model;
    }

    public function newQuery()
    {
        $this->query = $this->model->newQuery();
        return $this;
    }

    protected function dataFilters($filters)
    {
        return $filters;
    }

    protected function dataOrders($orders)
    {
        return $orders;
    }

    public function getAll(array $params = ['*'])
    {
        $this->newQuery()->eagerLoad();

        $limit = data_get($params, 'limit', $this->defaultLimit); // Laravel helpers data_get($array = [], $key, $defaultValue)
        $filters = Arr::except($params, ['limit', 'sorts']); // Filter without this colums ['limit', 'sorts']
        $sorts = data_get($params, 'sorts', $this->defaultSorts);
        $arrSorts = explode(',', $sorts);
        $orders = [];

        foreach ($arrSorts as $sort) {
            $item = explode(':', $sort);

            if (count($item) >= 2) {
                $key = $item[0];
                $value = $item[1];

                $orders[$key] = $value;

                $this->query->orderBy($key, $value);
            }
        }

        $filters = self::dataFilters($filters);
        $orders = self::dataOrders($orders);

        $result = $this->query
            ->filter($filters)
            ->sort($orders)
            ->paginate($limit)
            ->appends($params);

        $this->resetModel();

        return $result;
    }

    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->with = $relations;

        return $this;
    }

    public function eagerLoad()
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }
        return $this;
    }

    public function find(string $id, array $column = ['*'])
    {
        $this->newQuery()->eagerLoad();

        $reult = $this->query->find($id, $column);

        $this->resetModel();

        return $reult;
    }

    public function findOrFail(string $id, $column = ['*'])
    {
        $this->newQuery()->eagerLoad();

        $reult = $this->query->findOrFail($id, $column);

        $this->resetModel();

        return $reult;
    }

    // public function findByField($data = [], $column=['*']);

    public function create(array $data)
    {
        $object = $this->model->create($data);

        $this->resetModel();

        return $object;
    }

    public function update(array $data = ['*'], string $id)
    {
        $this->newQuery();

        $result = $this->query->findOrFail($id);
        $result->fill($data);
        $result->save();

        $this->resetModel();

        return $result;
    }

    public function destroy(string $id)
    {
        $this->newQuery();

        $result = $this->query->findOrFail($id);

        return $result->delete();
    }

    public function destroyMultipleByIds($ids)
    {
        return $this->model->destroy($ids);
    }

    public function getByColumn($column, $value, array $columns = ['*'])
    {
        $this->resetModel();

        $this->newQuery()->eagerLoad();

        return $this->query->where($column, $value)->first($columns);
    }

    public function getManyByColumn($column, $value, array $columns = ['*'])
    {
        $this->resetModel();

        $this->newQuery()->eagerLoad();

        return $this->query->where($column, $value)->get($columns);
    }

    protected function resetModel()
    {
        $this->makeModel();
        $this->query = '';
    }
}
