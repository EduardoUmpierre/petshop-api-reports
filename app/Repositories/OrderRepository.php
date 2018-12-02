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
     * @param int $id
     * @return array
     */
    public function findOneByIdWithMock(int $id): array {
        $order = $this->findOneById($id)->toArray();
        $order['products'] = $this->getProductMock($order['products']);
        $order['user'] = $this->getUserMock($order['user_id']);
        unset($order['user_id']);

        return $order;
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
        $query = Order::query()->select('id')->where('user_id', '=', $id);

        if (count($params) > 0) {
            if (isset($params['beginDate'])) {
                $query->whereDate('created_at', '>=', $params['beginDate']);
            }

            if (isset($params['endDate'])) {
                $query->whereDate('created_at', '<=', $params['endDate']);
            }
        }

        $orders = $query->get()->toArray();

        foreach ($orders as $key => $val) {
            $orders[$key] = $this->findOneByIdWithMock($val['id']);
        }

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
                $orders[$val['order_id']] = $this->findOneById($val['order_id'], false);
                $orders[$val['order_id']]['user'] = $this->getUserMock($orders[$val['order_id']]['user_id']);
                $orders[$val['order_id']]['quantity'] = $val['quantity'];
                unset($orders[$val['order_id']]['user_id']);
            } else {
                $orders[$val['order_id']]['quantity'] += $val['quantity'];
            }
        }

        $response['total'] = $total;
        $response['orders'] = array_values($orders);

        return $response;
    }

    /**
     * @param array $products
     * @return array
     */
    public function getProductMock(array $products): array
    {
        $mock = [
            1 => [
                'name' => 'Brinquedo para cachorro',
                'unit_price' => '10.50'
            ],
            2 => [
                'name' => 'Ração para cachorro',
                'unit_price' => '40.50'
            ],
            3 => [
                'name' => 'Aquário 50l',
                'unit_price' => '100'
            ],
            4 => [
                'name' => 'Ração para gato',
                'unit_price' => '12.45'
            ],
            5 => [
                'name' => 'Coleira para cachorro',
                'unit_price' => '10.50'
            ],
            6 => [
                'name' => 'Roda para hamster',
                'unit_price' => '10'
            ],
            7 => [
                'name' => 'Gaiola para pássaros',
                'unit_price' => '25'
            ],
        ];

        foreach ($products as $key => $val) {
            $mockItem = isset($mock[$val['id']]) ? $mock[$val['id']] : $mock[1];

            $products[$key]['name'] = $mockItem['name'];
            $products[$key]['unit_price'] = $mockItem['unit_price'];
            $products[$key]['id'] = $products[$key]['product_id'];
            unset($products[$key]['product_id']);
        }

        return $products;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getUserMock(int $id): array
    {
        $mock = [
            1 => 'Elias',
            2 => 'Eduardo',
            3 => 'Neon',
            4 => 'Ingrid',
            5 => 'Felipe',
            6 => 'Walter',
            7 => 'Ana',
            8 => 'Vivian'
        ];

        return [
            'id' => $id,
            'name' => isset($mock[$id]) ? $mock[$id] : $mock[1]
        ];
    }
}
