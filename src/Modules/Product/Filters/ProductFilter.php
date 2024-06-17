<?php

namespace Modules\Product\Filters;

use App\Filters\Filter;

/**
 * *There's common method exists in Filter class
 * *If you find any method exists in more than two or three filters add it in Filter class
 */
class ProductFilter extends Filter
{
    protected string $table = 'products';

    static $fields = [
        'name'                => 'nameLocal',
        'discount_from'       => 'discountFrom',
        'discount_to'         => 'discountTo',
        'price_from'          => 'priceFrom',
        'price_to'            => 'priceTo',
        'buying_status'       => 'buyingStatus',
        'selling_status'      => 'sellingStatus',
        'barcode'             => 'barcode',
        'product_type'        => 'productType',
        'product_id'          => 'productId',
        'manufacturer_id'     => 'manufacturerId',
    ];

    public function discountFrom($query, $value)
    {
        return $query->where("$this->table.discount_from", $value);
    }

    public function discountTo($query, $value)
    {
        return $query->where("$this->table.discount_to", $value);
    }

    public function priceFrom($query, $value)
    {
        return $query->where("$this->table.price_from", $value);
    }

    public function priceTo($query, $value)
    {
        return $query->where("$this->table.price_to", $value);
    }

    public function buyingStatus($query, $value)
    {
        return $query->where("$this->table.buying_status", $value);
    }

    public function sellingStatus($query, $value)
    {
        return $query->where("$this->table.selling_status", $value);
    }

    public function barcode($query, $value)
    {
        return $query->where("$this->table.barcode", $value);
    }

    public function productType($query, $value)
    {
        return $query->where("$this->table.product_type", $value);
    }

    public function productId($query, $value)
    {
        return $query->where("$this->table.product_id", $value);
    }

    public function manufacturerId($query, $value)
    {
        return $query->where("$this->table.manufacturer_id", $value);
    }
}
