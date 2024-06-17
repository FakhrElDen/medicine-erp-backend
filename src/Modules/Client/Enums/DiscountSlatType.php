<?php

namespace Modules\Client\Enums;

use Illuminate\Support\Facades\App;

class DiscountSlatType
{
    public const COMPANY = 0;

    public const VIP = 1;

    public const BULK1 = 2;

    public const BULK2 = 3;

    public const BULK3 = 4;

    public static function all()
    {
        App::isLocale('ar') ?
            $discount_slat_type = [
                [
                    "name" => 'شركه',
                    "value" => self::COMPANY,
                ],
                [
                    "name" => 'VIP',
                    "value" => self::VIP,
                ],
                [
                    "name" => 'جملة1',
                    "value" => self::BULK1,
                ],
                [
                    "name" => 'جملة2',
                    "value" => self::BULK2,
                ],
                [
                    "name" => 'جملة3',
                    "value" => self::BULK3,
                ],
            ]
            :
            $discount_slat_type = [
                [
                    "name" => 'company',
                    "value" => self::COMPANY,
                ],
                [
                    "name" => 'vip',
                    "value" => self::VIP,
                ],
                [
                    "name" => 'bulk1',
                    "value" => self::BULK1,
                ],
                [
                    "name" => 'bulk2',
                    "value" => self::BULK2,
                ],
                [
                    "name" => 'bulk3',
                    "value" => self::BULK3,
                ],
            ];
        return $discount_slat_type;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::COMPANY:
                return App::isLocale('ar') ? 'شركه' : 'company';
            case self::VIP:
                return App::isLocale('ar') ? 'VIP' : 'VIP';
            case self::BULK1:
                return App::isLocale('ar') ? 'جملة1' : 'BULK1';
            case self::BULK2:
                return App::isLocale('ar') ? 'جملة2' : 'BULK2';
            case self::BULK3:
                return App::isLocale('ar') ? 'جملة3' : 'BULK3';
            default:
                throw new \InvalidArgumentException("Invalid client type value: $value");
        }
    }
}
