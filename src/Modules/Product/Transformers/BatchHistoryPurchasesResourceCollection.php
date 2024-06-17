<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BatchHistoryPurchasesResourceCollection extends ResourceCollection
{
    public function __construct($resource, protected $product_prices)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map->toArray($request, $this->product_prices)->all();
    }
}
