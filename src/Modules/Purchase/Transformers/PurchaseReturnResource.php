<?php

namespace Modules\Purchase\Transformers;

use Modules\User\Transformers\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReturnResource extends JsonResource
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
            'id'                    => $this->id,
            'supplier_id_number'    => $this->supplier_id_number,
            'supplier_name'         => $this->supplier_name,
            'note'                  => $this->note,
            'created_at'            => $this->created_at,
            'returned_items'        => $this->whenLoaded('returnedItems'),
            'total_returned_items'  => $this->returnedItems ? $this->returnedItems->count() : 0,
            'purchase'              => new PurchaseResource($this->whenLoaded('purchase')),
            'created_by'            => new UserResource($this->whenLoaded('createdBy')),
        ];
    }
}
