<?php

namespace Modules\Product\Enums;

class SlatType
{
    public const FIRST_SLAT = 0;

    public const SECOND_SLAT = 1;

    public static function all()
    {
        $data = [
            [
                'name' => 'first_slat',
                'value' => self::FIRST_SLAT,
            ],
            [
                'name' => 'second_slat',
                'value' => self::SECOND_SLAT,
            ],
        ];

        return $data;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::FIRST_SLAT:
                return 'first_slat';
            case self::SECOND_SLAT:
                return 'second_slat';
            default:
                throw new \InvalidArgumentException("Invalid slat type value: $value");
        }
    }
}
