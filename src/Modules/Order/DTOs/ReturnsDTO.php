<?php

namespace Modules\Order\DTOs;

use App\DTOs\DTO;
use Illuminate\Support\Collection;

class ReturnsDTO extends DTO
{
    public Collection $returnables;

    public function __construct(
        public int $pharmacy_id,
        public int $warehouse_id,
        public ?int $order_id,
        array $returnables,
    ) {
        $this->returnables = collect();

        foreach ($returnables as $r) {
            $this->returnables->push(new ReturnableDTO(
                $r['returnable_id'],
                $r['returnable_type'],
                $r['quantity'],
                $r['price'],
                $r['discount'],
                $r['total'],
                $r['reason'],
                $r['operating_number'],
                $r['expired_at'],
            ));
        }
    }
}
