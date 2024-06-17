<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProhibitedBatchResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Product\Transformers\ProhibitedBatchResource';

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
