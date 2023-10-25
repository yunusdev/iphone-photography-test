<?php

namespace App\Repositories;

use App\Contracts\BaseContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseContract
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param  array $attributes
     * @return Builder
     */
    public function create(array $attributes): Builder
    {
        return $this->model->create($attributes);
    }

    /**
     * @param array $filter
     * @param array $relationship
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return Builder[]|Collection
     */
    public function all(
        array $filter = [],
        array $relationship = [],
        array $columns = array('*'),
        string $orderBy = 'created_at',
        string $sortBy = 'asc'
    ): Collection|array
    {
        return $this->model->orderBy($orderBy, $sortBy)->where($filter)->with($relationship)->get($columns);
    }

    /**
     * @param string $id
     * @param array $relationship
     * @return Builder
     */
    public function find(string $id, array $relationship = []): Builder
    {
        return $this->findOneBy(['id' => $id], $relationship);
    }


    /**
     * @param  array $data
     * @return Collection
     */
    public function findBy(array $data): Collection
    {
        return $this->model->where($data)->get();
    }

}
