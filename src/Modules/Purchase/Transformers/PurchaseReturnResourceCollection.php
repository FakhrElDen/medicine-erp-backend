<?php

namespace Modules\Purchase\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseReturnResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Purchase\Transformers\PurchaseReturnResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
