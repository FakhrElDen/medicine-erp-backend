<?php

namespace Modules\Setting\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SettingResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Setting\Transformers\SettingResource';

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
