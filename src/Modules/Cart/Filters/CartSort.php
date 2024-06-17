<?php

namespace Modules\Order\Filters;

use App\Filters\Sort;

class CartSort extends Sort
{
    protected string $table = 'carts';

    static $fields = [
        'created_at' => 'createdAt',
    ];
}
