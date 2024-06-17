<?php

namespace Modules\Product\Filters;

use App\Filters\Sort;
use Illuminate\Support\Facades\DB;

class ProductSort extends Sort
{
    protected string $table = 'products';

    static $fields = [
        'name'          => 'name',
        'price'         => 'price',
        'type'          => 'type',
        'quantity'      => 'quantity',
        'manufacturer'  => 'manufacturer',
    ];

    public function price($query, $direction = 'desc')
    {
        return $query->orderBy("$this->table.price", $direction);
    }

    public function type($query, $direction = 'desc')
    {
        return $query->orderBy("$this->table.type", $direction);
    }

    public function quantity($query, $direction = 'desc')
    {
        return  $query->join('batches', 'batches.product_id', '=', 'products.id')
            ->select('products.*', DB::raw('SUM(batches.current_quantity) as total_current_quantity'))
            ->orderBy('total_current_quantity', $direction);
    }

    public function manufacturer($query, $direction = 'desc')
    {
        $query->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
            ->orderBy("manufacturers.name->$this->local", $direction)->select('products.*');
    }
}
