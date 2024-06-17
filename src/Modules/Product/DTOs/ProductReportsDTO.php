<?php

namespace Modules\Product\DTOs;

use App\DTOs\DTO;
use DateTime;
use Illuminate\Support\Carbon;

class ProductReportsDTO extends DTO
{
    public ?Carbon $from;

    public ?Carbon $to;

    public function __construct(
        public int $product_id,
        public ?int $warehouse_id = null,
        public ?int $user_id = null,
        public ?int $pharmacy_id = null,
        public ?int $supplier_id = null,
        string|DateTime|null $from = null,
        string|DateTime|null $to = null,
        public ?string $sort_by = null,
        public string $direction = 'asc'
    ) {
        $this->from = $from ? Carbon::parse($from) : null;
        $this->to = $to ? Carbon::parse($to) : null;
    }
}
