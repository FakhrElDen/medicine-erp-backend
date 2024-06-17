<?php

namespace Modules\Purchase\Filters;

use App\Filters\Sort;

class CartPurchaseSort extends Sort
{
    protected string $table = 'cart_purchases';

    static $fields = [
        'created_at'       => 'createdAt',
    ];
}
