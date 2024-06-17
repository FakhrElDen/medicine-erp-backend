<?php

namespace Modules\Product\Enums;

use Illuminate\Support\Facades\App;

class ProductBuyingStatus
{
    public const SHOWINBUY = 0;

    public const BANBUY = 1;

    public const BANBUYWITHHIDE = 2;

    public static function all()
    {
        App::isLocale('ar') ?
        $reasons = [
            [
                'name' => 'يظهر في الشراء',
                'value' => self::SHOWINBUY,
            ],
            [
                'name' => 'حظر شراء',
                'value' => self::BANBUY,
            ],
            [
                'name' => 'حظر شراء مع الاخفاء',
                'value' => self::BANBUYWITHHIDE,
            ],
        ]
        :
        $reasons = [
            [
                'name' => 'Show in buy',
                'value' => self::SHOWINBUY,
            ],
            [
                'name' => 'Ban buy',
                'value' => self::BANBUY,
            ],
            [
                'name' => 'Ban buy with hide',
                'value' => self::BANBUYWITHHIDE,
            ],
        ];

        return $reasons;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::BANBUY:
                return App::isLocale('ar') ? 'حظر شراء' : 'Ban buy';
            case self::SHOWINBUY:
                return App::isLocale('ar') ? 'يظهر في الشراء' : 'Show in buy';
            case self::BANBUYWITHHIDE:
                return App::isLocale('ar') ? 'حظر شراء مع الاخفاء' : 'Ban buy with hide';
            default:
                throw new \InvalidArgumentException("Invalid product manufacturing type value: $value");
        }
    }
}
