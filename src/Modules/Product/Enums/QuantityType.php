<?php

namespace Modules\Product\Enums;

use Illuminate\Support\Facades\App;

class QuantityType
{
    public const EQUAL_ZERO = 0;

    public const MORE_THAN_ZERO = 1;

    public static function all()
    {
        App::isLocale('ar') ?
        $data = [
            [
                'name' => 'كل',
                'value' => null,
            ],
            [
                'name' => 'تساوى صفر',
                'value' => self::EQUAL_ZERO,
            ],
            [
                'name' => 'أكبر من صفر',
                'value' => self::MORE_THAN_ZERO,
            ],
        ]
        :
        $data = [
            [
                'name' => 'All',
                'value' => null,
            ],
            [
                'name' => 'Equal zero',
                'value' => self::EQUAL_ZERO,
            ],
            [
                'name' => 'More than zero',
                'value' => self::MORE_THAN_ZERO,
            ],
        ];

        return $data;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::EQUAL_ZERO:
                return App::isLocale('ar') ? 'تساوى صفر' : 'Equal zore';
            case self::MORE_THAN_ZERO:
                return App::isLocale('ar') ? 'أكبر من صفر' : 'More than zero';
            default:
                throw new \InvalidArgumentException("Invalid product type value: $value");
        }
    }
}
