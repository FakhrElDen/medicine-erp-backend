<?php

namespace Modules\Order\DTOs;

use App\DTOs\DTO;
use DateTime;
use Illuminate\Support\Carbon;
use Modules\Cart\Entities\CartSubBatch;
use Modules\Product\Entities\Product;

class ReturnableDTO extends DTO
{
    public Carbon $expired_at;

    public function __construct(
        public int $returnable_id,
        public string $returnable_type,
        public int $quantity,
        public float $price,
        public float $discount,
        public float $total,
        public string $reason,
        public string|int $operating_number,
        string|DateTime $expired_at,
    ) {
        $this->returnable_type = match ($returnable_type) {
            'Product' => Product::class,
            'CartSubBatch' => CartSubBatch::class,
            default => $returnable_type,
        };

        $this->expired_at = Carbon::parse($expired_at);
    }
}
