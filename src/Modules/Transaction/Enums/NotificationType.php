<?php

namespace Modules\Transaction\Enums;

use Illuminate\Support\Facades\App;

class NotificationType
{
    public const DISCOUNT = 0;

    public const ADD = 1;

    public static function all()
    {
        return [
            'discount' => self::DISCOUNT,
            'add' => self::ADD,
        ];
    }

    public static function getStringValue($value)
    {
        switch ($value) {
            case self::DISCOUNT:
                return App::isLocale('ar') ? 'خصم' : 'discount';
            case self::ADD:
                return App::isLocale('ar') ? 'اضافة' : 'add';
            default:
                throw new \InvalidArgumentException("Invalid notification type value: $value");
        }
    }
}
