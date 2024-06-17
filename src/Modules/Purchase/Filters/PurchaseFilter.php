<?php

namespace Modules\Purchase\Filters;

use App\Filters\Filter;

/**
 * *There's common method exists in Filter class
 * *If you find any method exists in more than two or three filters add it in Filter class
 */
class PurchaseFilter extends Filter
{
    protected string $table = 'purchases';

    static $fields = [
        'purchase_number'   => 'purchaseNumber',
        'status'            => 'status',
        'created_at'        => 'createdAt',
        'warehouse_id'      => 'warehouseId',
        'client_id'         => 'clientId',
        'pharmacy_id'       => 'pharmacyId',
        'supplier_id'       => 'supplierId',
    ];

    public function purchaseNumber($query, $value)
    {
        return $query->where("$this->table.purchase_number", $value);
    }

    public function supplierId($query, $value)
    {
        return $query->where("$this->table.supplier_id", $value);
    }
}
