<?php

namespace App\Repositories;

use App\Product;

class OrderProductsRepository
{
    /**
     * @param array $products
     * @param int $orderId
     */
    public function create(array $products, int $orderId)
    {
        foreach ($products as $key => $val) {
            Product::query()->create([
                'order_id' => $orderId,
                'product_id' => $val['id'],
                'quantity' => $val['quantity']
            ]);            
        }
    }
}
