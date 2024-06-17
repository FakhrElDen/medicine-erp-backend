<?php

namespace Modules\Product\Filters;

use App\Filters\Filter;
use Illuminate\Support\Carbon;

/**
 * *There's common method exists in Filter class
 * *If you find any method exists in more than two or three filters add it in Filter class
 */
class SubBatchFilter extends Filter
{
    protected string $table = 'sub_batches';

    static $fields = [
        'current_quantity'       => 'currentQuantity',
        'stand'                  => 'stand',
        'shelf'                  => 'shelf',
        'corridor_id'            => 'corridorId',
        'batch_id'               => 'batchId',
        'purchase_id'            => 'purchaseId',
        'price'                  => 'price',
        'cart_purchase_id'       => 'cartPurchaseId',
        'discount'               => 'discount',
        'expired_at'             => 'expiredAt',
    ];

    public function batchId($query, $value)
    {
        return $query->where("$this->table.batch_id", $value);
    }

    public function corridorId($query, $value)
    {
        return $query->where("$this->table.corridor_id", $value);
    }

    public function purchaseId($query, $value)
    {
        return $query->where("$this->table.purchase_id", $value);
    }

    public function currentQuantity($query, $value)
    {
        return $query->where("$this->table.current_quantity", $value);
    }

    public function stand($query, $value)
    {
        return $query->where("$this->table.stand", $value);
    }

    public function price($query, $value)
    {
        return $query->where("$this->table.price", $value);
    }

    public function discount($query, $value)
    {
        return $query->where("$this->table.discount", $value);
    }

    public function shelf($query, $value)
    {
        return $query->where("$this->table.shelf", $value);
    }

    public function expiredAt($query, $value)
    {
        return  $query->whereRaw("DATE_FORMAT($this->table.expired_at, '%Y-%m') = ?", [Carbon::parse($value)->format('Y-m')]);
    }
}
