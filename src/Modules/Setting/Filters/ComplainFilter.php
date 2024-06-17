<?php

namespace Modules\Setting\Filters;

use App\Filters\Filter;

/**
 * *There's common method exists in Filter class
 * *If you find any method exists in more than two or three filters add it in Filter class
 */
class ComplainFilter extends Filter
{
    protected string $table = 'complains';

    static $fields = [
        'client_id'    => 'clientId',
    ];
}
