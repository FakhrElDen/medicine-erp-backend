<?php

namespace Modules\Purchase\DTOs;

use App\DTOs\DTO;

class PurchaseReturnDTO extends DTO
{
    public function __construct(
        public int $reason,
        public int $quantity,
        public int $public_price,
        public int $cart_purchase_id
    ) {
        //
    }
}
