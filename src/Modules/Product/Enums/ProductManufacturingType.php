<?php

namespace Modules\Product\Enums;

use Illuminate\Support\Facades\App;

class ProductManufacturingType
{
    public const NOTHING = 0;

    public const LOCAL = 1;

    public const IMPORTED = 2;

    public const INVESTMENT = 3;

    public static function all()
    {
        App::isLocale('ar') ?
        $reasons = [
            [
                "name" => 'بدون',
                "value" => self::NOTHING,
            ],
            [
                "name" => 'محلي',
                "value" => self::LOCAL,
            ],
            [
                "name" => 'مستورد',
                "value" => self::IMPORTED,
            ],
            [
                "name" => 'استثمارى',
                "value" => self::INVESTMENT,
            ],
        ]
        :
        $reasons = [
            [
                "name" => 'Nothing',
                "value" => self::NOTHING,
            ],
            [
                "name" => 'Local',
                "value" => self::LOCAL,
            ],
            [
                "name" => 'Imported',
                "value" => self::IMPORTED,
            ],
            [
                "name" => 'Investment',
                "value" => self::INVESTMENT,
            ],
        ];

        return $reasons;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::NOTHING:
                return App::isLocale('ar') ? 'بدون' : 'Nothing';
            case self::LOCAL:
                return App::isLocale('ar') ? 'محلي' : 'Local';
            case self::IMPORTED:
                return App::isLocale('ar') ? 'مستورد' : 'Imported';
            case self::INVESTMENT:
                return App::isLocale('ar') ? 'استثماري' : 'Investment';
            default:
                throw new \InvalidArgumentException("Invalid product manufacturing type value: $value");
        }
    }
}
