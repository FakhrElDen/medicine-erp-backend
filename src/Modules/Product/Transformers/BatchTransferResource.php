<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Warehouse\Transformers\TransferResource;

class BatchTransferResource extends JsonResource
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
            'batch_transfer_id'         => $this->when($this->id, $this->id),
            'quantity_before_transfer'  => $this->when($this->quantity_before_transfer, $this->quantity_before_transfer),
            'quantity_transferred'      => $this->when($this->quantity_transferred, $this->quantity_transferred),
            'discount'                  => $this->when($this->discount, $this->discount),
            'total'                     => $this->when($this->total, $this->total),
            'transferred_at'            => $this->when($this->transferred_at, $this->transferred_at),
            'created_at'                => $this->when($this->created_at, $this->created_at),
            'batch'                     => new BatchResource($this->whenLoaded('batch', $this->batch)),
            'transfer'                  => new TransferResource($this->whenLoaded('transfer', $this->transfer)),
            'main_location'             => $this->when($this->main_location, $this->main_location),

        ];
    }
}
