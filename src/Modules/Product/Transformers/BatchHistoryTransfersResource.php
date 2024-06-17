<?php

namespace Modules\Product\Transformers;

use App\Traits\CheckNestedRelations;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Transformers\UserResource;
use Modules\Warehouse\Transformers\WarehouseResource;

class BatchHistoryTransfersResource extends JsonResource
{
    use CheckNestedRelations;

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
            'from_warehouse' => $this->when(
                $this->checkRelation('subject.transfer.fromWarehouse'),
                fn () => new WarehouseResource($this->subject->transfer->fromWarehouse)
            ),
            'to_warehouse' => $this->when(
                $this->checkRelation('batch.warehouse'),
                fn () => new WarehouseResource($this->batch->warehouse)
            ),
            'created_at' => $this->created_at,
            'product' => $this->when(
                $this->checkRelation('batch.product'),
                fn () => new ProductResource($this->batch->product),
            ),
            'quantity_before' => $this->warehouse_product_quantity_before,
            'amount' => abs($this->amount),
            'quantity_after' => $this->warehouse_product_quantity_after,
            'user' => new UserResource($this->whenLoaded('user')),
            'batch' => new BatchResource($this->whenLoaded('batch')),
        ];
    }
}
