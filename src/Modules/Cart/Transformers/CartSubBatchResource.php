<?php

namespace Modules\Cart\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Cart\Enums\CartSubBatchStatus;
use Modules\Order\Transformers\ReturnablesResource;
use Modules\Product\Transformers\BatchResource;
use Modules\User\Transformers\UserResource;

class CartSubBatchResource extends JsonResource
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
            'cart_sub_batch_id' => $this->id,
            'ordered_quantity' => $this->quantity < $this->bonus ? $this->quantity : $this->quantity - $this->bonus,
            'available_quantity' => $this->quantity - $this->returned_quantity,
            'cart_sub_batch_status' => CartSubBatchStatus::getStringValue($this->status),
            'cart_sub_batch_status_value' => $this->status,
            'price' => $this->price,
            'color' => $this->color,
            'total' => $this->total,
            'bonus' => $this->bonus,
            'batch' => new BatchResource($this->whenLoaded('batch')),
            'inventoried' => $this->status == CartSubBatchStatus::INVENTORIED ? true : false,
            'inventoried_at' => $this->inventoried_at,
            'inventoried_by' => new UserResource($this->whenLoaded('inventoriedBy')),
            'returns_values' => $this->when($this->pivot, new ReturnablesResource($this->pivot)),
            'returned_alert_message' => trans('product::message.returned_alert', [
                'available_quantity' => $this->quantity - $this->returned_quantity,
                'total_quantity' => $this->quantity,
            ]),
        ];
    }
}
