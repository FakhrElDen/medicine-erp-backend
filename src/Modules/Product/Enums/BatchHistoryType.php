<?php

namespace Modules\Product\Enums;

use Illuminate\Database\Eloquent\Model;
use Modules\Cart\Entities\CartSubBatch;
use Modules\Order\Entities\Returnables;
use Modules\Product\Entities\Batch;
use Modules\Purchase\Entities\CartPurchase;
use Modules\Purchase\Entities\CartPurchasesReturn;
use Modules\Warehouse\Entities\CartSubBatchWarehouse;
use Modules\Warehouse\Entities\BatchTransfer;

class BatchHistoryType
{
    public const PURCHASE = 1;

    public const PURCHASE_RETURN = 2;

    public const SALES = 3;

    public const SALES_RETURN = 4;

    public const SETTLEMENT = 5;

    public const CORRECTION = 6;

    public const TRANSFER = 7;

    public const EDIT = 8;

    public function __construct(public int $value)
    {
        if ($value < 1 || $value > 8) {
            throw new \UnexpectedValueException();
        }
    }

    public static function fromModel(?Model $subject): int
    {
        return match (get_class($subject)) {
            CartPurchase::class => self::PURCHASE,
            CartPurchasesReturn::class => self::PURCHASE_RETURN,
            CartSubBatch::class => self::SALES,
            Returnables::class => self::SALES_RETURN,
            CartSubBatchWarehouse::class => self::SETTLEMENT,
            BatchTransfer::class => self::TRANSFER,
            Batch::class => self::EDIT,
        };
    }
}
