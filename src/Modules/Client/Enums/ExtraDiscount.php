<?php

namespace Modules\Client\Enums;

class ExtraDiscount
{
    public const NO_DISCOUNT = 0;

    public const FIRST = 1;

    public const SECOND = 2;

    public const THIRD = 3;

    public const FOURTH = 4;

    public const FIFTH = 5;

    public const SIXTH = 6;

    public static function all()
    {
        return [
            [
                "name" => '0',
                "value" => self::NO_DISCOUNT,
            ],
            [
                "name" => '1%',
                "value" => self::FIRST,
            ],
            [
                "name" => '2%',
                "value" => self::SECOND,
            ],
            [
                "name" => '3%',
                "value" => self::THIRD,
            ],
            [
                "name" => '4%',
                "value" => self::FOURTH,
            ],
            [
                "name" => '5%',
                "value" => self::FIFTH,
            ],
            [
                "name" => '6%',
                "value" => self::SIXTH,
            ]
        ];
    }
}
