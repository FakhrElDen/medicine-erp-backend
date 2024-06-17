<?php

namespace Modules\Purchase\Filters;

use App\Filters\Sort;

class PurchaseSort extends Sort
{
    protected string $table = 'purchases';

    static $fields = [
        'created_at'       => 'createdAt',
    ];
}
