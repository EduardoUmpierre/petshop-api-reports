<?php

namespace App\Repositories;

use App\Order;
use App\Repositories\OrderProductsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderRepository
{
    private $orderProductsRepository;

    /**
     * @param OrderProductsRepository $opr
     */
    public function __construct(OrderProductsRepository $opr) 
    {
        $this->orderProductsRepository = $opr;
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return Order::query()->with('products')->findOrFail($id);
    }

    /**
     * @param array $params
     * @return $this|Model
     */
    public function create(array $params): Model
    {
        $order = Order::query()->create($params);
        $this->orderProductsRepository->create($params['products'], $order->id);

        return $this->findOneById($order->id);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Collection|Model
     */
    public function updateStatus(int $id, int $status): Model
    {
        $product = Order::query()->findOrFail($id);
        $product->status = $status;
        $product->update();

        return $product;
    }
}
