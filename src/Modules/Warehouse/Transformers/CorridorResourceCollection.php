<?php

namespace Modules\Warehouse\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CorridorResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Warehouse\Transformers\CorridorResource';

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
