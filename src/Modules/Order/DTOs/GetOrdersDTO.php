<?php

namespace Modules\Order\DTOs;

use App\DTOs\DTO;
use Illuminate\Support\Carbon;

class GetOrdersDTO extends DTO
{
    public ?Carbon $from;
    public ?Carbon $to;

    public function __construct(
        public ?int $order_id = null,
        public ?int $client_id = null,
        public ?int $warehouse_id = null,
        public ?int $pharmacy_id = null,
        public ?int $city_id = null,
        public ?int $area_id = null,
        public ?int $track_id = null,
        public ?int $sales_id = null,
        public ?int $status = null,
        public ?int $order_number = null,
        public ?string $created_at = null,
        public ?int $pagination_number = null,
        public ?int $payment_type = null,
        public ?int $product_id = null,
        public ?string $operating_number = null,
        ?string $from = null,
        ?string $to = null,
        public ?string $expired_at = null,
        public ?string $sort_by = null,
    ) {
        $this->from = $from ? Carbon::createFromFormat('Y-m-d', $from) : null;
        $this->to = $to ? Carbon::createFromFormat('Y-m-d', $to) : null;
    }
}
