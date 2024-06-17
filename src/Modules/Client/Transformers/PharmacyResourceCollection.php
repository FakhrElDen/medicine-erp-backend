<?php

namespace Modules\Client\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PharmacyResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Client\Transformers\PharmacyResource';

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
