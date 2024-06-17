<?php

namespace Modules\Client\Filters;

use App\Filters\Filter;

class ClientFilter extends Filter
{
    protected string $table = 'clients';

    static $fields = [
        'name'  => 'name',
        'code'  => 'code',
    ];
}
