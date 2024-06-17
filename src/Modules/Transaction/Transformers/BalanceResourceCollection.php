<?php

namespace Modules\Transaction\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BalanceResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Transaction\Transformers\BalanceResource';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'         => $this->collection,
            'total_amount' => $this->collection->sum('amount'),
        ];
        return parent::toArray($request);
    }
}
