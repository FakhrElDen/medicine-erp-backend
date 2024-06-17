<?php

namespace Modules\Product\Filters;

use App\Filters\Filter;

/**
 * *There's common method exists in Filter class
 * *If you find any method exists in more than two or three filters add it in Filter class
 */
class BatchFilter extends Filter
{
    protected string $table = 'batches';

    static $fields = [
        'total_quantity'          => 'totalQuantity',
        'operating_number'        => 'operatingNumber',
        'product_id'              => 'productId'
    ];

    public function operatingNumber($query, $value)
    {
        return $query->where("$this->table.operating_number", $value);
    }

    public function productId($query, $value)
    {
        return $query->where("$this->table.product_id", $value);
    }
}
