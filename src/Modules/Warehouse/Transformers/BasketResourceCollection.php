<?php

namespace Modules\Warehouse\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BasketResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Warehouse\Transformers\BasketResource';

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
