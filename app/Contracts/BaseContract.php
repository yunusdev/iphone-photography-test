<?php
namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface BaseContract
{
    /**
     * Create a model instance
     *
     * @param  array $attributes
     * @return Builder
     */
    public function create(array $attributes): Builder;

    /**
     * Return all model rows
     *
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
    );

    /**
     * Find one by ID
     *
     * @param string $id
     * @param array $relationship
     * @return Builder
     */
    public function find(string $id, array $relationship = []): Builder;

    /**
     * Find based on a different column
     *
     * @param  array $data
     * @return Collection
     */
    public function findBy(array $data): Collection;

}
