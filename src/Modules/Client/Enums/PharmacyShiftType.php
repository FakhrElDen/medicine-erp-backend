<?php

namespace Modules\Client\Enums;

use Illuminate\Support\Facades\App;

class PharmacyShiftType
{
    public const FROM_1_TO_3_AM = 0;

    public const FROM_4_TO_6_AM = 1;

    public const FROM_7_TO_9_AM = 2;

    public const FROM_10_TO_12_AM = 3;

    public const FROM_1_TO_3_PM = 4;

    public const FROM_4_TO_6_PM = 5;

    public const FROM_7_TO_9_PM = 6;

    public const FROM_10_TO_12_PM = 7;

    public static function all()
    {
        App::isLocale('ar') ?
            $pharmacy_shift_type = [
                [
                    "name" => 'ص 3 - 1 ص',
                    "value" => self::FROM_1_TO_3_AM,
                ],
                [
                    "name" => 'ص 6 - 4 ص',
                    "value" => self::FROM_4_TO_6_AM,
                ],
                [
                    "name" => 'ص 9 - 7 ص',
                    "value" => self::FROM_7_TO_9_AM,
                ],
                [
                    "name" => 'ص 12 - 10 ص',
                    "value" => self::FROM_10_TO_12_AM,
                ],
                [
                    "name" => 'م 3 - 1 م',
                    "value" => self::FROM_1_TO_3_PM,
                ],
                [
                    "name" => 'م 6 - 4 م',
                    "value" => self::FROM_4_TO_6_PM,
                ],
                [
                    "name" => 'م 9 - 7 م',
                    "value" => self::FROM_7_TO_9_PM,
                ],
                [
                    "name" => 'م 12 - 10 م',
                    "value" => self::FROM_10_TO_12_PM,
                ],
            ]
            :
            $pharmacy_shift_type = [
                [
                    "name" => 'from_1_to_3_am',
                    "value" => self::FROM_1_TO_3_AM,
                ],
                [
                    "name" => 'from_4_to_6_am',
                    "value" => self::FROM_4_TO_6_AM,
                ],
                [
                    "name" => 'from_7_to_9_am',
                    "value" => self::FROM_7_TO_9_AM,
                ],
                [
                    "name" => 'from_10_to_12_am',
                    "value" => self::FROM_10_TO_12_AM,
                ],
                [
                    "name" => 'from_1_to_3_pm',
                    "value" => self::FROM_1_TO_3_PM,
                ],
                [
                    "name" => 'from_4_to_6_pm',
                    "value" => self::FROM_4_TO_6_PM,
                ],
                [
                    "name" => 'from_9_to_7_pm',
                    "value" => self::FROM_7_TO_9_PM,
                ],
                [
                    "name" => 'from_12_to_10_pm',
                    "value" => self::FROM_10_TO_12_PM,
                ],
            ];
        return $pharmacy_shift_type;
    }

    public static function getStringValue($value)
    {
        switch ($value) {
            case self::FROM_1_TO_3_AM:
                return App::isLocale('ar') ? 'ص 3 - 1 ص' : '1 AM - 3 AM';
            case self::FROM_4_TO_6_AM:
                return App::isLocale('ar') ? 'ص 6 - 4 ص' : '4 AM - 6 AM';
            case self::FROM_7_TO_9_AM:
                return App::isLocale('ar') ? 'ص 9 - 7 ص' : '7 AM - 9 AM';
            case self::FROM_10_TO_12_AM:
                return App::isLocale('ar') ? 'ص 12 - 10 ص' : '10 AM - 12 AM';
            case self::FROM_1_TO_3_PM:
                return App::isLocale('ar') ? 'م 3 - 1 م' : '1 PM - 3 PM';
            case self::FROM_4_TO_6_PM:
                return App::isLocale('ar') ? 'م 6 - 4 م' : '1 PM - 3 PM';
            case self::FROM_7_TO_9_PM:
                return App::isLocale('ar') ? 'م 9 - 7 م' : '7 PM - 9 PM';
            case self::FROM_10_TO_12_PM:
                return App::isLocale('ar') ? 'م 12 - 10 م' : '10 PM - 12 PM';
            default:
                throw new \InvalidArgumentException("Invalid pharmacy status value: $value");
        }
    }
}
