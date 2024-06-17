<?php

namespace Modules\Area\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CityResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Area\Transformers\CityResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $pharmacies_count_sum = $this->collection->sum('pharmacies_count');

        return [
            'data' => $this->collection,
            'pharmacies_count_sum' => $pharmacies_count_sum,
        ];
    }
}
