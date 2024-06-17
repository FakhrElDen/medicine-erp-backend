<?php

namespace Modules\Order\Filters;

use App\Filters\Filter;

/**
 * *There's common method exists in Filter class
 * *If you find any method exists in more than two or three filters add it in Filter class
 */
class OrderFilter extends Filter
{
    protected string $table = 'orders';

    static $fields = [
        'status'          => 'status',
        'order_number'    => 'orderNumber',
        'invoice_number'  => 'orderNumber',
        'created_at'      => 'createdAt',
        'client_id'       => 'clientId',
        'warehouse_id'    => 'warehouseId',
        'pharmacy_id'     => 'pharmacyId',
        'city_id'         => 'cityId',
        'area_id'         => 'areaId',
        'track_id'        => 'trackId',
        'delivery_id'     => 'deliveryId',
        'sales_id'        => 'salesId',
    ];

    public function orderNumber($query, $value)
    {
        return $query->where("$this->table.order_number", $value);
    }

    public function cityId($query, $value)
    {
        return $query->where("$this->table.city_id", $value);
    }

    public function areaId($query, $value)
    {
        return $query->where("$this->table.area_id", $value);
    }

    public function trackId($query, $value)
    {
        return $query->where("$this->table.track_id", $value);
    }

    public function deliveryId($query, $value)
    {
        return $query->where("$this->table.delivery_id", $value);
    }

    public function salesId($query, $value)
    {
        return $query->where("$this->table.sales_id", $value);
    }
}
