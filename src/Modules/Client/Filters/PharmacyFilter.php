<?php

namespace Modules\Client\Filters;

use App\Filters\Filter;

class PharmacyFilter extends Filter
{
    protected string $table = 'pharmacies';

    static $fields = [
        'name'                          => 'name',
        'code'                          => 'code',
        'all'                           => 'all',
        'active'                        => 'active',
        'target'                        => 'target',
        'discount_slat'                 => 'discountSlat',
        'iterate_available_quantity'    => 'iterateAvailableQuantity',
        'payment_type'                  => 'paymentType',
        'minimum_target'                => 'minimumTarget',
        'debt_limit'                    => 'debtLimit',
        'payment_period'                => 'paymentPeriod',
        'extra_discount'                => 'extraDiscount',
        'follow_up'                     => 'followUp',
        'call_shift'                    => 'callShift',
        'city_id'                       => 'cityId',
        'area_id'                       => 'areaId',
        'track_id'                      => 'trackId',
    ];

    public function all($query, $value)
    {
        return $query->where("$this->table.all", $value);
    }

    public function active($query, $value)
    {
        return $query->where("$this->table.active", $value);
    }

    public function target($query, $value)
    {
        return $query->where("$this->table.target", $value);
    }

    public function discountSlat($query, $value)
    {
        return $query->where("$this->table.discount_slat", $value);
    }

    public function iterateAvailableQuantity($query, $value)
    {
        return $query->where("$this->table.iterate_available_quantity", $value);
    }

    public function paymentType($query, $value)
    {
        return $query->where("$this->table.payment_type", $value);
    }

    public function minimumTarget($query, $value)
    {
        return $query->where("$this->table.minimum_target", $value);
    }

    public function debtLimit($query, $value)
    {
        return $query->where("$this->table.debt_limit", $value);
    }

    public function paymentPeriod($query, $value)
    {
        return $query->where("$this->table.payment_period", $value);
    }

    public function extraDiscount($query, $value)
    {
        return $query->where("$this->table.extra_discount", $value);
    }

    public function followUp($query, $value)
    {
        return $query->where("$this->table.follow_up", $value);
    }

    public function callShift($query, $value)
    {
        return $query->where("$this->table.call_shift", $value);
    }

    public function cityId($query, $value)
    {
        return $query->where("$this->table.city_id", $value);
    }

    public function areaId($query, $value)
    {
        return $query->where("$this->table.area_id", $value);
    }

    public function trackId($query, $value)
    {
        return $query->where("$this->table.track_id", $value);
    }
}
