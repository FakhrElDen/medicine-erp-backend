<?php

namespace Modules\Order\Filters;

class OrderSort
{
    static $fields = [
        'id'         => 'id',
        'created_at' => 'createdAt',
    ];

    public function id($query, $direction = 'desc')
    {
        return $query->orderBy('id', $direction);
    }

    public function createdAt($query, $direction = 'desc')
    {
        return $query->orderBy('created_at', $direction);
    }
}
