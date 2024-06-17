<?php

namespace Modules\Client\Enums;

use Illuminate\Support\Facades\App;

class ClientType
{
    public const BRAND = 0;

    public const SUPPLIER = 1;

    public const PHARMACY = 2;

    public static function all()
    {
        return [
            'brand' => self::BRAND,
            'supplier' => self::SUPPLIER,
            'pharmacy' => self::PHARMACY,
        ];
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::BRAND:
                return App::isLocale('ar') ? 'مجموعة' : 'brand';
            case self::SUPPLIER:
                return App::isLocale('ar') ? 'مندوب' : 'supplier';
            case self::PHARMACY:
                return App::isLocale('ar') ? 'صيدلية' : 'pharmacy';
            default:
                throw new \InvalidArgumentException("Invalid client type value: $value");
        }
    }
}
