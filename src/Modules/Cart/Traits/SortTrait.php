<?php

namespace Modules\Cart\Traits;

trait SortTrait
{
    public function scopeSortByProductName($query, $local)
    {
        return $query->leftJoin('products', 'products.id', '=', 'carts.product_id')
            ->select('carts.*', 'products.name')
            ->orderBy("products.name->$local", $input['direction'] ?? 'desc');
    }

    public function scopeSortByManufacturerName($query, $local)
    {
        return $query->leftJoin('products', 'products.id', '=', 'carts.product_id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
            ->select('carts.*', 'manufacturers.name')
            ->orderBy("manufacturers.name->$local", $input['direction'] ?? 'desc');
    }

    public function scopeSortByCorridor($query)
    {
        return $query->leftJoin('corridors', 'corridors.id', '=', 'carts.corridor_id')
            ->select('carts.*', 'corridors.number')
            ->orderBy('corridors.number', $input['direction'] ?? 'desc');
    }
}
