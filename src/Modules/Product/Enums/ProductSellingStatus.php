<?php

namespace Modules\Product\Enums;

use Illuminate\Support\Facades\App;

class ProductSellingStatus
{
    public const SHOWINSELL = 0;

    public const BANSELL = 1;

    public const BANSELLWITHHIDE = 2;

    public static function all()
    {
        App::isLocale('ar') ?
        $reasons = [
            [
                'name' => 'يظهر في البيع',
                'value' => self::SHOWINSELL,
            ],
            [
                'name' => 'حظر بيع',
                'value' => self::BANSELL,
            ],
            [
                'name' => 'حظر بيع مع الاخفاء',
                'value' => self::BANSELLWITHHIDE,
            ],
        ]
        :
        $reasons = [
            [
                'name' => 'Show in sell',
                'value' => self::SHOWINSELL,
            ],
            [
                'name' => 'Ban sell',
                'value' => self::BANSELL,
            ],
            [
                'name' => 'Ban sell with hide',
                'value' => self::BANSELLWITHHIDE,
            ],
        ];

        return $reasons;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::BANSELL:
                return App::isLocale('ar') ? 'حظر بيع' : 'Ban sell';
            case self::SHOWINSELL:
                return App::isLocale('ar') ? 'يظهر في البيع' : 'Show in sell';
            case self::BANSELLWITHHIDE:
                return App::isLocale('ar') ? 'حظر بيع مع الاخفاء' : 'Ban sell with hide';
            default:
                throw new \InvalidArgumentException("Invalid product manufacturing type value: $value");
        }
    }
}
