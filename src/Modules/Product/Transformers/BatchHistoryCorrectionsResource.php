<?php

namespace Modules\Product\Transformers;

use App\Traits\CheckNestedRelations;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Warehouse\Transformers\WarehouseResource;

class BatchHistoryCorrectionsResource extends JsonResource
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
            'batch' => new BatchResource($this->whenLoaded('batch')),
            'product' => new ProductResource(
                $this->when($this->checkRelation('batch.product'), fn () => $this->batch->product)
            ),
            'user' => new WarehouseResource($this->whenLoaded('user')),
            'warehouse' => new WarehouseResource(
                $this->when($this->checkRelation('batch.warehouse'), fn () => $this->batch->warehouse)
            ),
            'created_at' => $this->created_at,
            'quantity_before' => $this->warehouse_product_quantity_before,
            'excess' => $this->amount > 0 ? $this->amount : null,
            'shortage' => $this->amount < 0 ? abs($this->amount) : null,
            'quantity_after' => $this->warehouse_product_quantity_after,
        ];
    }
}
