<?php

namespace Modules\Warehouse\Enums;

use Illuminate\Support\Facades\App;

class WarehouseType
{
    public const MAIN = 0;

    public const SALES = 1;

    public const PURCHASES = 2;

    public const SCARCE = 3;

    public const SETTLEMENT = 4;

    public static function all()
    {
        App::isLocale('ar') ?
            $warehouse_type = [
                [
                    "name" => 'قطاعي',
                    "value" => self::MAIN,
                ],
                [
                    "name" => 'نواقص',
                    "value" => self::SCARCE,
                ],
                [
                    "name" => 'مشتريات',
                    "value" => self::PURCHASES,
                ],
                [
                    "name" => 'جملة',
                    "value" => self::SALES,
                ],
                [
                    "name" => 'تسوية',
                    "value" => self::SETTLEMENT,
                ],
            ]
            :
            $warehouse_type = [
                [
                    "name" => 'main',
                    "value" => self::MAIN,
                ],
                [
                    "name" => 'scarce',
                    "value" => self::SCARCE,
                ],
                [
                    "name" => 'purchases',
                    "value" => self::PURCHASES,
                ],
                [
                    "name" => 'sales',
                    "value" => self::SALES,
                ],
                [
                    "name" => 'settlement',
                    "value" => self::SETTLEMENT,
                ],
            ];
        return $warehouse_type;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::MAIN:
                return 'main';
            case self::SCARCE:
                return 'scarce';
            case self::PURCHASES:
                return 'purchases';
            case self::SALES:
                return 'sales';
            case self::SETTLEMENT:
                return 'settlement';
            default:
                throw new \InvalidArgumentException("Invalid warehouse type value: $value");
        }
    }
}
