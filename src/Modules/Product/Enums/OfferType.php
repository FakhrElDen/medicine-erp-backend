<?php

namespace Modules\Product\Enums;

class OfferType
{
    public const PERCENTAGE = 0;

    public const QUANTITY = 1;

    public static function all()
    {
        $data = [
            [
                'name' => 'percentage',
                'value' => self::PERCENTAGE,
            ],
            [
                'name' => 'quantity',
                'value' => self::QUANTITY,
            ],
        ];

        return $data;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::PERCENTAGE:
                return 'percentage';
            case self::QUANTITY:
                return 'quantity';
            default:
                throw new \InvalidArgumentException("Invalid offer type value: $value");
        }
    }
}
