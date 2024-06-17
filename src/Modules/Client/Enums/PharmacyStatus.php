<?php

namespace Modules\Client\Enums;

use Illuminate\Support\Facades\App;

class PharmacyStatus
{
    public const DEALING = 0;

    public const SUSPENDED = 1;

    public const NOT_DEALING = 2;

    public static function all()
    {
        App::isLocale('ar') ?
            $pharmacy_status = [
                [
                    "name" => 'متاح التعامل',
                    "value" => self::DEALING,
                ],
                [
                    "name" => 'موقوف التعامل',
                    "value" => self::SUSPENDED,
                ],
                [
                    "name" => 'غير متعامل',
                    "value" => self::NOT_DEALING,
                ],
            ]
            :
            $pharmacy_status = [
                [
                    "name" => 'dealing',
                    "value" => self::DEALING,
                ],
                [
                    "name" => 'suspended',
                    "value" => self::SUSPENDED,
                ],
                [
                    "name" => 'not_dealing',
                    "value" => self::NOT_DEALING,
                ],
            ];
        return $pharmacy_status;
    }

    public static function getStringValue($value)
    {
        switch ($value) {
            case self::DEALING:
                return App::isLocale('ar') ? 'متاح التعامل' : 'Dealing';
            case self::SUSPENDED:
                return App::isLocale('ar') ? 'موقوف التعامل' : 'Suspended';
            case self::NOT_DEALING:
                return App::isLocale('ar') ? 'غير متعامل' : 'Not Dealing';
            default:
                throw new \InvalidArgumentException("Invalid pharmacy status value: $value");
        }
    }
}
