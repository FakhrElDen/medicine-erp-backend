<?php

namespace Modules\Product\Filters;

use App\Filters\Filter;
use Illuminate\Support\Carbon;

/**
 * *There's common method exists in Filter class
 * *If you find any method exists in more than two or three filters add it in Filter class
 */
class ProhibitedBatchFilter extends Filter
{
    protected string $table = 'prohibited_batches';

    static $fields = [
        'post_number'       => 'postNumber',
        'post_reason'       => 'postReason',
        'operating_number'  => 'operatingNumber',
        'expiry_date'       => 'expiryDate',
        'created_at'        => 'createdAt',
        'created_by'        => 'createdBy',
    ];

    public function expiryDate($query, $value)
    {
        return  $query->whereRaw("DATE_FORMAT($this->table.expired_at, '%Y-%m') = ?", [Carbon::parse($value)->format('Y-m')]);
    }

    public function operatingNumber($query, $value)
    {
        return $query->where("$this->table.operating_number", $value);
    }

    public function postNumber($query, $value)
    {
        return $query->where("$this->table.post_number", $value);
    }

    public function postReason($query, $value)
    {
        return $query->where("$this->table.post_reason", $value);
    }
}
