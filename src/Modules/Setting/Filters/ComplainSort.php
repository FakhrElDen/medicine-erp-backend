<?php

namespace Modules\Product\Filters;

use App\Filters\Sort;

class ComplainSort extends Sort
{
    protected string $table = 'complains';

    static $fields = [
        'created_at'    => 'createdAt',
    ];
}
