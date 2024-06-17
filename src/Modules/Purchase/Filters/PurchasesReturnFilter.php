<?php

namespace Modules\Purchase\Filters;

use App\Filters\Filter;

/**
 * *There's common method exists in Filter class
* *If you find any method exists in more than two or three filters add it in Filter class
 */
class PurchasesReturnFilter extends Filter
{
    protected string $table = 'purchases_returns';

    static $fields = [
        'return_id'         => 'returnId',
        'purchase_id'       => 'purchaseId',
        'created_by'        => 'createdBy',
    ];

    public function returnId($query, $value)
    {
        return $query->where("$this->table.return_id", $value);
    }

    public function purchaseId($query, $value)
    {
        return $query->where("$this->table.purchase_id", $value);
    }
}
