<?php

namespace Modules\Cart\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CartSubBatchResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Product\Transformers\CartSubBatchResource';

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
