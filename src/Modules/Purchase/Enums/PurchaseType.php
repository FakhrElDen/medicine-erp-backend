<?php

namespace Modules\Purchase\Enums;

use Illuminate\Support\Facades\App;

class PurchaseType
{
    public const MANUFACTURER = 0;

    public const PHARMACY = 1;

    public static function all()
    {
        App::isLocale('ar') ?
            $purchase_type = [
                [
                    "name" => 'أذن لمصنع',
                    "value" => self::MANUFACTURER,
                ],
                [
                    "name" => 'أذن لصيدلية',
                    "value" => self::PHARMACY,
                ]
            ]
            :
            $purchase_type = [
                [
                    "name" => 'manufacturer',
                    "value" => self::MANUFACTURER,
                ],
                [
                    "name" => 'pharmacy',
                    "value" => self::PHARMACY,
                ]
            ];
        return $purchase_type;
    }

    public static function getStringValue($value)
    {
        switch ($value) {
            case self::MANUFACTURER:
                return App::isLocale('ar') ? 'أذن لمصنع' : 'manufacturer';
            case self::PHARMACY:
                return App::isLocale('ar') ? 'أذن لصيدلية' : 'pharmacy';
            default:
                throw new \InvalidArgumentException("Invalid purchase type value: $value");
        }
    }
}
