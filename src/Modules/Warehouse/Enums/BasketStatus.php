<?php

namespace Modules\Warehouse\Enums;

class BasketStatus
{
    public const UNDAMAGED = 0;

    public const DAMAGED = 1;

    public static function all()
    {
        return [
            'undamaged' => self::UNDAMAGED,
            'damaged' => self::DAMAGED,
        ];
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::UNDAMAGED:
                return 'undamaged';
            case self::DAMAGED:
                return 'damaged';
            default:
                throw new \InvalidArgumentException("Invalid basket status value: $value");
        }
    }
}
