<?php

namespace App\Repositories;

use App\Schedule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ScheduleRepository
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Schedule::query()->with('user:id,name')->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return Schedule::query()->with('user:id,name')->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        return Schedule::query()->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function update(array $params, int $id): Model
    {
        $product = Schedule::query()->findOrFail($id);
        $product->update($params);
        return $product;
    }

    /**
     * @param int $id
     * @return null
     */
    public function delete(int $id)
    {
        Schedule::query()->findOrFail($id)->delete();
        return null;
    }
}
