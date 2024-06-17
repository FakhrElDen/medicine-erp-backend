<?php

namespace Modules\Purchase\Enums;

use Illuminate\Support\Facades\App;

class CartPurchaseStatus
{
    public const NON_INVENTORIED = 0;

    public const INVENTORIED = 1;

    public const SEMI_INVENTORIED = 2;

    public static function all()
    {
        App::isLocale('ar') ?
            $cart_purchase_status = [
                [
                    "name" => 'غير مجروده',
                    "value" => self::NON_INVENTORIED,
                ],
                [
                    "name" => 'تم جردها',
                    "value" => self::INVENTORIED,
                ],
                [
                    "name" => 'فى مرحلة الجرد',
                    "value" => self::SEMI_INVENTORIED,
                ]
            ]
            :
            $cart_purchase_status = [
                [
                    "name" => 'non inventoried',
                    "value" => self::NON_INVENTORIED,
                ],
                [
                    "name" => 'inventoried',
                    "value" => self::INVENTORIED,
                ],
                [
                    "name" => 'semi inventoried',
                    "value" => self::SEMI_INVENTORIED,
                ]
            ];
        return $cart_purchase_status;
    }

    public static function getStringValue($value)
    {
        switch ($value) {
            case self::NON_INVENTORIED:
                return App::isLocale('ar') ? 'غير مجروده' : 'non inventoried';
            case self::INVENTORIED:
                return App::isLocale('ar') ? 'تم جردها' : 'inventoried';
            case self::SEMI_INVENTORIED:
                return App::isLocale('ar') ? 'فى مرحلة الجرد' : 'semi inventoried';
            default:
                throw new \InvalidArgumentException("Invalid cart purchase status value: $value");
        }
    }
}
