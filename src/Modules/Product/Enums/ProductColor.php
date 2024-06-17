<?php

namespace Modules\Product\Enums;

class ProductColor
{
    public const HIGH_PRICE = 0;

    public const IN_SETTLEMENT_WAREHOUSE = 1;

    public static function all()
    {
        return [
            'high_price' => self::HIGH_PRICE,
            'in_settlement_warehouse' => self::IN_SETTLEMENT_WAREHOUSE,
        ];
    }

    public static function getStringValue($value)
    {
        switch ($value) {
            case self::HIGH_PRICE:
                return '#3F497F';
            case self::IN_SETTLEMENT_WAREHOUSE:
                return '#FF874F';
            default:
                throw new \InvalidArgumentException("Invalid product color value: $value");
        }
    }
}
