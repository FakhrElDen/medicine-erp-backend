<?php

namespace Modules\Cart\Filters;

use App\Filters\Filter;

class CartFilter extends Filter
{
    protected string $table = 'carts';

    static $fields= [
        'status'        => 'status',
        'created_at'    => 'createdAt',
        'order_id'      => 'orderId',
        'client_id'     => 'clientId',
        'warehouse_id'  => 'warehouseId',
        'pharmacy_id'   => 'pharmacyId',
        'product_id'    => 'productId',
        'sales_id'      => 'salesId',
    ];

    public function orderId($query, $value)
    {
        return $query->where("$this->table.id", $value);
    }

    public function productId($query, $value)
    {
        return $query->where("$this->table.product_id", $value);
    }

    public function salesId($query, $value)
    {
        return $query->where("$this->table.sales_id", $value);
    }
}
