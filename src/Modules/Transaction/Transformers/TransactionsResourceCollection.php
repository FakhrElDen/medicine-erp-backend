<?php

namespace Modules\Transaction\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionsResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Transaction\Transformers\TransactionsResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $total_sum = $this->collection->sum('total');

        return [
            'data' => $this->collection,
            'total_sum' => $total_sum,
        ];
    }
}
