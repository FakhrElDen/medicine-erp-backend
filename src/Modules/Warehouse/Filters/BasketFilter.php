<?php

namespace Modules\Warehouse\Filters;

use App\Filters\Filter;

/**
 * *There's common method exists in Filter class
* *If you find any method exists in more than two or three filters add it in Filter class
 */
class BasketFilter extends Filter
{
    protected string $table = 'baskets';

    static $fields = [
        'number'    => 'number',
    ];
}
