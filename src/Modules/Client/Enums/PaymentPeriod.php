<?php

namespace Modules\Client\Enums;

use Illuminate\Support\Facades\App;

class PaymentPeriod
{
    public const NONE = 0;

    public const ONE_DAY = 1;

    public const TWO_DAYS = 2;

    public const THREE_DAYS = 3;

    public const FOUR_DAYS = 4;

    public static function all()
    {
        App::isLocale('ar') ?
            $payment_period = [
                [
                    "name" => 'لا توجد فترة سماح',
                    "value" => self::NONE,
                ],
                [
                    "name" => 'فترة السماح 1 يوم',
                    "value" => self::ONE_DAY,
                ],
                [
                    "name" => 'فترة السماح 2 يوم',
                    "value" => self::TWO_DAYS,
                ],
                [
                    "name" => 'فترة السماح 3 يوم',
                    "value" => self::THREE_DAYS,
                ],
                [
                    "name" => 'فترة السماح 4 يوم',
                    "value" => self::FOUR_DAYS,
                ],
            ]
            :
            $payment_period = [
                [
                    "name" => 'no_grace_period',
                    "value" => self::NONE,
                ],
                [
                    "name" => '1_day_grace_period',
                    "value" => self::ONE_DAY,
                ],
                [
                    "name" => '2_days_grace_period',
                    "value" => self::TWO_DAYS,
                ],
                [
                    "name" => '3_days_grace_period',
                    "value" => self::THREE_DAYS,
                ],
                [
                    "name" => '4_days_grace_period',
                    "value" => self::FOUR_DAYS,
                ],
            ];
        return $payment_period;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::NONE:
                return App::isLocale('ar') ? 'لا توجد فترة سماح' : 'no grace period';
            case self::ONE_DAY:
                return App::isLocale('ar') ? 'فترة السماح 1 يوم' : '1 day grace period';
            case self::TWO_DAYS:
                return App::isLocale('ar') ? 'فترة السماح 2 يوم' : '2 day grace period';
            case self::THREE_DAYS:
                return App::isLocale('ar') ? 'فترة السماح 3 يوم' : '3 day grace period';
            case self::FOUR_DAYS:
                return App::isLocale('ar') ? 'فترة السماح 4 يوم' : '4 day grace period';
            default:
                throw new \InvalidArgumentException("Invalid payment period value: $value");
        }
    }
}
