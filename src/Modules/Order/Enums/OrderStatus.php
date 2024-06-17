<?php

namespace Modules\Order\Enums;

use Illuminate\Support\Facades\App;

class OrderStatus
{
    public const EDITABLE = 0;

    public const IN_PREPARING = 1;

    public const PREPARED_AND_NON_INVENTORY = 2;

    public const INVENTORIED = 3;

    public const DELIVERING = 4;

    public const COMPLETED = 5;

    public const CANCELED = 6;

    public static function all()
    {
        $data = [
            [
                'name' => 'editable',
                'value' => self::EDITABLE,
            ],
            [
                'name' => 'in_preparing',
                'value' => self::IN_PREPARING,
            ],
            [
                'name' => 'prepared_and_non_inventory',
                'value' => self::PREPARED_AND_NON_INVENTORY,
            ],
            [
                'name' => 'inventoried',
                'value' => self::INVENTORIED,
            ],
            [
                'name' => 'delivering',
                'value' => self::DELIVERING,
            ],
            [
                'name' => 'completed',
                'value' => self::COMPLETED,
            ],
            [
                'name' => 'canceled',
                'value' => self::CANCELED,
            ],
        ];

        return $data;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::EDITABLE:
                return App::isLocale('ar') ? 'مفتوحة' : 'Editable'; // 1
            case self::IN_PREPARING:
                return App::isLocale('ar') ? ' مغلق/في التحضير' : 'In Progress'; // 2
            case self::PREPARED_AND_NON_INVENTORY:
                return App::isLocale('ar') ? 'تم التحضير/لم يتم جردها' : 'Prepared and non inventory'; // 3
            case self::INVENTORIED:
                return App::isLocale('ar') ? 'تم جردها' : 'Inventoried'; // 4
            case self::DELIVERING:
                return App::isLocale('ar') ? 'في التوصيل' : 'Delivering'; // 5
            case self::COMPLETED:
                return App::isLocale('ar') ? 'تم التوصبل' : 'Completed'; // 6
            case self::CANCELED:
                return App::isLocale('ar') ? 'ألغيت' : 'Canceled'; // extra & unused
            default:
                throw new \InvalidArgumentException("Invalid order status value: $value");
        }
    }
}
