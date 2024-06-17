<?php

namespace Modules\Order\Filters;

use App\Filters\Filter;

/**
 * *There's common method exists in Filter class
 * *If you find any method exists in more than two or three filters add it in Filter class
 */
class ReturnFilter extends Filter
{
    protected string $table = 'returns';

    static $fields = [
        'warehouse_id'    => 'warehouseId',
        'pharmacy_id'     => 'pharmacyId',
    ];
}
