<?php

namespace Modules\Product\Enums;

use Illuminate\Support\Facades\App;

class ProductType
{
    public const LIQUID = 0;

    public const TABLET = 1;

    public const INJECTIONS = 2;

    public const CAPSULES = 3;

    public const DROPS = 4;

    public const SUPPOSITORY = 5;

    public static function all()
    {
        App::isLocale('ar') ?
        $reasons = [
            [
                "name" => 'الحقن',
                "value" => self::INJECTIONS,
            ],
            [
                "name" => 'سائل',
                "value" => self::LIQUID,
            ],
            [
                "name" => 'قرص',
                "value" => self::TABLET,
            ],
            [
                "name" => 'كبسولات',
                "value" => self::CAPSULES,
            ],
            [
                "name" => 'قطرة',
                "value" => self::DROPS,
            ],
            [
                "name" => 'لبوس',
                "value" => self::SUPPOSITORY,
            ],
        ]
        :
        $reasons = [
            [
                "name" => 'Injections',
                "value" => self::INJECTIONS,
            ],
            [
                "name" => 'Liquid',
                "value" => self::LIQUID,
            ],
            [
                "name" => 'Tablet',
                "value" => self::TABLET,
            ],
            [
                "name" => 'Capsules',
                "value" => self::CAPSULES,
            ],
            [
                "name" => 'drops',
                "value" => self::DROPS,
            ],
            [
                "name" => 'suppository',
                "value" => self::SUPPOSITORY,
            ],
        ];

        return $reasons;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::INJECTIONS:
                return App::isLocale('ar') ? 'الحقن' : 'Injections';
            case self::LIQUID:
                return App::isLocale('ar') ? 'سائل' : 'Liquid';
            case self::TABLET:
                return App::isLocale('ar') ? 'قرص' : 'Tablet';
            case self::CAPSULES:
                return App::isLocale('ar') ? 'كبسولات' : 'Capsules';
            case self::DROPS:
                return App::isLocale('ar') ? 'قطرة' : 'drops';
            case self::SUPPOSITORY:
                return App::isLocale('ar') ? 'لبوس' : 'suppository';
            default:
                throw new \InvalidArgumentException("Invalid product type value: $value");
        }
    }
}
