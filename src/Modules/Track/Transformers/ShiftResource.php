<?php

namespace Modules\Track\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'from' => Carbon::parse($this->from)->format('g:i A'),
            'to' => Carbon::parse($this->to)->format('g:i A'),
        ];
    }
}
