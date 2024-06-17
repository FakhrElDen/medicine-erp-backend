<?php

namespace Modules\Cart\Enums;

class CartSubBatchStatus
{
    public const IN_PROGRESS = 0;

    public const NOT_FOUND = 1;

    public const COMPLETED = 2;

    public const INVENTORIED = 3;

    public static function all()
    {
        $data = [
            [
                'name' => 'In Progress',
                'value' => self::IN_PROGRESS,
            ],
            [
                'name' => 'Nothing',
                'value' => self::NOT_FOUND,
            ],
            [
                'name' => 'Completed',
                'value' => self::COMPLETED,
            ],
            [
                'name' => 'Investment',
                'value' => self::INVENTORIED,
            ],
        ];

        return $data;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::NOT_FOUND:
                return 'not_found';
            case self::IN_PROGRESS:
                return 'in_progress';
            case self::COMPLETED:
                return 'completed';
            case self::INVENTORIED:
                return 'inventoried';
            default:
                throw new \InvalidArgumentException("Invalid cart batch status value: $value");
        }
    }
}
