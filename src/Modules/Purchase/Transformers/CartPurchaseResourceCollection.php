<?php

namespace Modules\Purchase\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CartPurchaseResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Purchase\Transformers\CartPurchaseResource';

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
