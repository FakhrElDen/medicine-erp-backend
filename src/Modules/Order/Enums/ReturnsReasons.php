<?php

namespace Modules\Order\Enums;

use Illuminate\Support\Facades\App;

class ReturnsReasons
{
    public const NOT_ORDERED = 0;

    public const NOT_DELIVER = 1;

    public const EXPIRE_DATE = 2;

    public const QUANTITY_NOT_REQUIRED = 3;

    public const WRONG_OPERATION_NUMBER = 4;

    public const EXPIRED = 5;

    public const DISCOUNT = 6;

    public const PREPARING_FAULT = 7;

    public static function all()
    {
        App::isLocale('ar') ?
            $reasons = [
                [
                    "name" => 'غير مطلوب',
                    "value" => self::NOT_ORDERED,
                ],
                [
                    "name" => 'خطأ تحضير',
                    "value" => self::PREPARING_FAULT,
                ],
                [
                    "name" => 'لم يصل',
                    "value" => self::NOT_DELIVER,
                ],
                [
                    "name" => 'صلاحية',
                    "value" => self::EXPIRE_DATE,
                ],
                [
                    "name" => 'الكمية غير مطلوبه',
                    "value" => self::QUANTITY_NOT_REQUIRED,
                ],
                [
                    "name" => 'تشغيله مضروبة',
                    "value" => self::WRONG_OPERATION_NUMBER,
                ],
                [
                    "name" => 'تالف',
                    "value" => self::EXPIRED,
                ],
                [
                    "name" => 'خصم',
                    "value" => self::DISCOUNT,
                ],
            ]
            :
            $reasons = [
                [
                    "name" => 'Not ordered',
                    "value" => self::NOT_ORDERED,
                ],
                [
                    "name" => 'Preparing fault',
                    "value" => self::PREPARING_FAULT,
                ],
                [
                    "name" => 'Not Deliver',
                    "value" => self::NOT_DELIVER,
                ],
                [
                    "name" => 'Expire Date',
                    "value" => self::EXPIRE_DATE,
                ],
                [
                    "name" => 'Quantity not required',
                    "value" => self::QUANTITY_NOT_REQUIRED,
                ],
                [
                    "name" => 'Worng operation number',
                    "value" => self::WRONG_OPERATION_NUMBER,
                ],
                [
                    "name" => 'Expired',
                    "value" => self::EXPIRED,
                ],
                [
                    "name" => 'Discount',
                    "value" => self::DISCOUNT,
                ],
            ];

        return $reasons;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::NOT_ORDERED:
                return App::isLocale('ar') ? 'غير مطلوب' : 'Not ordered'; // 0
            case self::PREPARING_FAULT:
                return App::isLocale('ar') ? 'خطأ تحضير' : 'Preparing fault'; // 7
            case self::NOT_DELIVER:
                return App::isLocale('ar') ? 'لم يصل' : 'Not Deliver'; //1
            case self::EXPIRE_DATE:
                return App::isLocale('ar') ? 'صلاحية' : 'Expire Date'; // 2
            case self::QUANTITY_NOT_REQUIRED:
                return App::isLocale('ar') ? 'الكمية غير مطلوبه' : 'Quantity not required'; // 3
            case self::WRONG_OPERATION_NUMBER:
                return App::isLocale('ar') ? 'تشغيله مضروبة' : 'Worng operation number'; //4
            case self::EXPIRED:
                return App::isLocale('ar') ? 'تالف' : 'Expired'; // 5
            case self::DISCOUNT:
                return App::isLocale('ar') ? 'خصم' : 'Discount'; // 6
            default:
                throw new \InvalidArgumentException("Invalid order status value: $value");
        }
    }
}
