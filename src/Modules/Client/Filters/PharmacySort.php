<?php

namespace Modules\Client\Filters;

use App\Filters\Sort;

class PharmacySort extends Sort
{
    protected string $table = 'pharmacies';

    static $fields = [
        'created_at' => 'createdAt',
    ];
}
