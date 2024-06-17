<?php

namespace Modules\Warehouse\DTOs;

use DateTime;
use Illuminate\Support\Carbon;

class SettlementIndexDTO
{
    public ?Carbon $from;

    public ?Carbon $to;

    public function __construct(
        public ?int $product_id = null,
        public ?int $warehouse_id = null,
        public ?int $reviewed_by = null,
        public ?int $pharmacy_id = null,
        string|DateTime|null $from = null,
        string|DateTime|null $to = null,
    ) {
        $this->from = $from ? Carbon::parse($from) : null;
        $this->to = $to ? Carbon::parse($to) : null;
    }

    /**
     * checks if a property has been set or not
     */
    public function has($property)
    {
        return !is_null($this->{$property});
    }
}
