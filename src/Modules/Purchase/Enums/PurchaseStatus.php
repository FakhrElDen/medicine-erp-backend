<?php

namespace Modules\Purchase\Enums;

use Illuminate\Support\Facades\App;

class PurchaseStatus
{
    public const Unreviewed = 0;

    public const Reviewed = 1;

    public static function all()
    {
        App::isLocale('ar') ?
            $purchase_status = [
                [
                    "name" => 'لم تتم مراجعته',
                    "value" => self::Unreviewed,
                ],
                [
                    "name" => 'تم مراجعته',
                    "value" => self::Reviewed,
                ]
            ]
            :
            $purchase_status = [
                [
                    "name" => 'unreviewed',
                    "value" => self::Unreviewed,
                ],
                [
                    "name" => 'reviewed',
                    "value" => self::Reviewed,
                ]
            ];
        return $purchase_status;
    }

    public static function getStringValue($value)
    {
        switch ($value) {
            case self::Unreviewed:
                return App::isLocale('ar') ? 'لم تتم مراجعته' : 'Unreviewed';
            case self::Reviewed:
                return App::isLocale('ar') ? 'تم مراجعته' : 'Reviewed';
            default:
                throw new \InvalidArgumentException("Invalid purchase status value: $value");
        }
    }
}
