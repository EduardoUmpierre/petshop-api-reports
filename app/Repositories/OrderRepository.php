<?php

namespace App\Repositories;

use App\Order;
use App\Product;
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
     * @param bool $withProducts
     * @param array $fields
     * @return Model
     */
    public function findOneById(int $id, bool $withProducts = true, array $fields = ['*']): Model
    {
        if (!$withProducts) {
            return Order::query()->findOrFail($id, $fields);
        }

        return Order::query()->with('products')->findOrFail($id, $fields);
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
     * @return array
     */
    public function getCustomerReport(int $id, array $params): array
    {
        $response = [];
        $query = Order::query()->with('products')->where('id', '=', $id);

        if (count($params) > 0) {
            if (isset($params['beginDate'])) {
                $query->whereDate('created_at', '>=', $params['beginDate']);
            }

            if (isset($params['endDate'])) {
                $query->whereDate('created_at', '<=', $params['endDate']);
            }
        }

        $orders = $query->get();
        $response['orders'] = $orders;
        $response['total'] = count($orders);

        return $response;
    }

    /**
     * @param int $id
     * @param array $params
     * @return array
     */
    public function getProductReport(int $id, array $params): array
    {
        $response = [];
        $orders = [];
        $total = 0;

        $query = Product::query()->where('product_id', '=', $id);

        if (count($params) > 0) {
            if (isset($params['beginDate'])) {
                $query->whereDate('created_at', '>=', $params['beginDate']);
            }

            if (isset($params['endDate'])) {
                $query->whereDate('created_at', '<=', $params['endDate']);
            }
        }

        $products = $query->get();

        foreach ($products as $key => $val) {
            $total += $val['quantity'];

            if (!isset($orders[$val['order_id']])) {
                $orders[$val['order_id']] = $this->findOneById($val['order_id'],false);
                $orders[$val['order_id']]['quantity'] = $val['quantity'];
            } else {
                $orders[$val['order_id']]['quantity'] += $val['quantity'];
            }
        }

        $response['total'] = $total;
        $response['orders'] = array_values($orders);

        return $response;
    }
}
