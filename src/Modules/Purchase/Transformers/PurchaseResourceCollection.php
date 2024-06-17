<?php

namespace Modules\Purchase\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Purchase\Transformers\PurchaseResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $total_price_sum = $this->collection->sum('total_price');

        return [
            'data' => $this->collection,
            'total_price_sum' => $total_price_sum,
        ];
    }
}
