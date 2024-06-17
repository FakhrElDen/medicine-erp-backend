<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Cart\Transformers\CartSubBatchResource;

class BatchResource extends JsonResource
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
            'id'                     => $this->id,
            'packet'                 => $this->packet,
            'package'                => $this->package,
            'operating_number'       => $this->operating_number,
            'expired_at'             => $this->expired_at,
            'batch_price'            => $this->price,
            'created_at'             => $this->created_at,
            'cart_sub_batch'         => $this->whenPivotLoaded('cart_sub_batch', fn () => new CartSubBatchResource($this->pivot)),
            'batch_transfer'         => $this->whenPivotLoaded('batch_transfer', fn () => new BatchTransferResource($this->pivot)),
            'sub_batches'            => new SubBatchResourceCollection($this->whenLoaded('subBatches')),
            'product'                => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
