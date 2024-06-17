<?php

namespace Modules\Listing\Enums;

use Illuminate\Support\Facades\App;

class ListingType
{
    public const MORNING = 0;

    public const NIGHT = 1;

    public static function all()
    {
        App::isLocale('ar') ?
            $listing_type = [
                [
                    "name" => 'صباحي',
                    "value" => self::MORNING,
                ],
                [
                    "name" => 'مسائي',
                    "value" => self::NIGHT,
                ],
            ]
            :
            $listing_type = [
                [
                    "name" => 'morning',
                    "value" => self::MORNING,
                ],
                [
                    "name" => 'night',
                    "value" => self::NIGHT,
                ],
            ];
        return $listing_type;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::MORNING:
                return App::isLocale('ar') ? 'صباحي' : 'Morning';
            case self::NIGHT:
                return App::isLocale('ar') ? 'مسائي' : 'Night';
            default:
                throw new \InvalidArgumentException("Invalid listing type value: $value");
        }
    }
}
