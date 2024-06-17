<?php

namespace Modules\Client\Enums;

class PharmaciesType
{
    public const MAIN = 0;

    public const Sub = 1;

    public static function all()
    {
        $data = [
            [
                'name' => 'Main',
                'value' => self::MAIN,
            ],
            [
                'name' => 'Sub',
                'value' => self::Sub,
            ],
        ];

        return $data;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::MAIN:
                return 'main';
            case self::Sub:
                return 'sub';
            default:
                throw new \InvalidArgumentException("Invalid pharmacy type value: $value");
        }
    }
}
