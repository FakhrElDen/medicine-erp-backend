<?php

namespace Modules\Client\Enums;

use Illuminate\Support\Facades\App;

class PaymentType
{
    public const COLLECTING_ON_SATURDAY = 0;

    public const COLLECTING_ON_SUNDAY = 1;

    public const COLLECTING_ON_MONDAY = 2;

    public const COLLECTING_ON_TUESDAY = 3;

    public const COLLECTING_ON_WEDNESDAY = 4;

    public const COLLECTING_ON_THURSDAY = 5;

    public const COLLECTING_ON_FRIDAY = 6;

    public const CASH_ONLY = 7;

    public const CASH = 8;

    public const INVOICE_AND_INVOICE = 9;

    public const COLLECTING_IN_TEN_DAYS = 10;

    public const COLLECTING_IN_FIFTEEN_DAYS = 11;

    public const COLLECTING_IN_MONTH = 12;

    public static function all()
    {
        App::isLocale('ar') ?
            $payment_type = [
                [
                    "name" => 'تحصيل السبت',
                    "value" => self::COLLECTING_ON_SATURDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'تحصيل الاحد',
                    "value" => self::COLLECTING_ON_SUNDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'تحصيل الاثنين',
                    "value" => self::COLLECTING_ON_MONDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'تحصيل الثلاثاء',
                    "value" => self::COLLECTING_ON_TUESDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'تحصيل الاربعاء',
                    "value" => self::COLLECTING_ON_WEDNESDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'تحصيل الخميس',
                    "value" => self::COLLECTING_ON_THURSDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'تحصيل الجمعة',
                    "value" => self::COLLECTING_ON_FRIDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'نقدي فقط',
                    "value" => self::CASH_ONLY,
                    "payment_period" => PaymentPeriod::NONE,
                ],
                [
                    "name" => 'نقدي',
                    "value" => self::CASH,
                    "payment_period" => PaymentPeriod::ONE_DAY,
                ],
                [
                    "name" => 'فاتورة و فاتورة',
                    "value" => self::INVOICE_AND_INVOICE,
                    "payment_period" => PaymentPeriod::ONE_DAY,
                    "payment_period" => PaymentPeriod::THREE_DAYS,
                ],
                [
                    "name" => 'تحصيل 10 ايام',
                    "value" => self::COLLECTING_IN_TEN_DAYS,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'تحصيل 15 ايام',
                    "value" => self::COLLECTING_IN_FIFTEEN_DAYS,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'تحصيل شهر',
                    "value" => self::COLLECTING_IN_MONTH,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
            ]
            :
            $payment_type = [
                [
                    "name" => 'collecting_on_Saturday',
                    "value" => self::COLLECTING_ON_SATURDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'collecting_on_Sunday',
                    "value" => self::COLLECTING_ON_SUNDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'collecting_on_Monday',
                    "value" => self::COLLECTING_ON_MONDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'collecting_on_Tuesday',
                    "value" => self::COLLECTING_ON_TUESDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'collecting_on_Wednesday',
                    "value" => self::COLLECTING_ON_WEDNESDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'collecting_on_Thursday',
                    "value" => self::COLLECTING_ON_THURSDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'collecting_on_Friday',
                    "value" => self::COLLECTING_ON_FRIDAY,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'cash_only',
                    "value" => self::CASH_ONLY,
                    "payment_period" => PaymentPeriod::NONE,
                ],
                [
                    "name" => 'cash',
                    "value" => self::CASH,
                    "payment_period" => PaymentPeriod::ONE_DAY,
                ],
                [
                    "name" => 'invoice_and_invoice',
                    "value" => self::INVOICE_AND_INVOICE,
                    "payment_period" => PaymentPeriod::THREE_DAYS,
                ],
                [
                    "name" => 'collecting_in_ten days',
                    "value" => self::COLLECTING_IN_TEN_DAYS,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'collecting_in_fifteen days',
                    "value" => self::COLLECTING_IN_FIFTEEN_DAYS,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
                [
                    "name" => 'collecting_in_month',
                    "value" => self::COLLECTING_IN_MONTH,
                    "payment_period" => PaymentPeriod::TWO_DAYS,
                ],
            ];
        return $payment_type;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::COLLECTING_ON_SATURDAY:
                return App::isLocale('ar') ? 'تحصيل السبت' : 'collecting on Saturday';
            case self::COLLECTING_ON_SUNDAY:
                return App::isLocale('ar') ? 'تحصيل الاحد' : 'collecting on Sunday';
            case self::COLLECTING_ON_MONDAY:
                return App::isLocale('ar') ? 'تحصيل الاثنين' : 'collecting on Monday';
            case self::COLLECTING_ON_TUESDAY:
                return App::isLocale('ar') ? 'تحصيل الثلاثاء' : 'collecting on Tuesday';
            case self::COLLECTING_ON_WEDNESDAY:
                return App::isLocale('ar') ? 'تحصيل الاربعاء' : 'collecting on Wednesday';
            case self::COLLECTING_ON_THURSDAY:
                return App::isLocale('ar') ? 'تحصيل الخميس' : 'collecting on Thursday';
            case self::COLLECTING_ON_FRIDAY:
                return App::isLocale('ar') ? 'تحصيل الجمعة' : 'collecting on Friday';
            case self::CASH_ONLY:
                return App::isLocale('ar') ? 'نقدي فقط' : 'cash only';
            case self::CASH:
                return App::isLocale('ar') ? 'نقدي' : 'Cash';
            case self::INVOICE_AND_INVOICE:
                return App::isLocale('ar') ? 'فاتورة و فاتورة' : 'invoice and invoice';
            case self::COLLECTING_IN_TEN_DAYS:
                return App::isLocale('ar') ? 'تحصيل 10 ايام' : 'collecting in ten days';
            case self::COLLECTING_IN_FIFTEEN_DAYS:
                return App::isLocale('ar') ? 'تحصيل 15 ايام' : 'collecting in fifteen days';
            case self::COLLECTING_IN_MONTH:
                return App::isLocale('ar') ? 'تحصيل شهر' : 'collecting in month';
            default:
                throw new \InvalidArgumentException("Invalid payment type value: $value");
        }
    }
}
