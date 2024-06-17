<?php

namespace Modules\Listing\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ListingResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Listing\Transformers\ListingResource';

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
