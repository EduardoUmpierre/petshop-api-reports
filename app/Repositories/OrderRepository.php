<?php

namespace App\Repositories;

use App\Order;
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
     * @param int $id
     * @param string $status
     * @return Model
     */
    public function updateStatus(int $id, string $status): Model
    {
        $product = Order::query()->findOrFail($id);
        $product->status = $status;
        $product->update();

        return $product;
    }

    /**
     * @param int $id
     * @param array $params
     * @return Collection
     */
    public function getCustomerReport(int $id, array $params): Collection
    {
        $query = Order::query()->with('products')->where('id', '=', $id);

        if (count($params) > 0) {
            if (isset($params['beginDate'])) {
                $query->whereDate('created_at', '>=', $params['beginDate']);
            }

            if (isset($params['endDate'])) {
                $query->whereDate('created_at', '<=', $params['endDate']);
            }
        }

        return $query->get();
    }
}
