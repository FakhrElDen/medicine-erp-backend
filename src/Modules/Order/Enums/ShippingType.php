<?php

namespace Modules\Order\Enums;

use Illuminate\Support\Facades\App;

class ShippingType
{
    public const NORMAL = 0;

    public const SPECIAL = 1;

    public static function all()
    {
        $data = [
            [
                'name' => 'normal',
                'value' => self::NORMAL,
            ],
            [
                'name' => 'Special',
                'value' => self::SPECIAL,
            ],
        ];

        return $data;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::NORMAL:
                return App::isLocale('ar') ? 'توصيل عادي' : 'Normal';
            case self::SPECIAL:
                return App::isLocale('ar') ? 'توصيل خاص' : 'Special';
            default:
                throw new \InvalidArgumentException("Invalid shipping type value: $value");
        }
    }
}
