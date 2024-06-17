<?php

namespace Modules\Product\Transformers;

use App\Traits\CheckNestedRelations;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Enums\ProductBuyingStatus;
use Modules\Product\Enums\ProductManufacturingType;
use Modules\Product\Enums\ProductSellingStatus;
use Modules\Product\Enums\ProductType;
use Modules\Product\Transformers\BatchResourceCollection;
use Modules\Product\Transformers\OfferResourceCollection;
use Modules\Warehouse\Transformers\WarehouseResourceCollection;

class ProductResource extends JsonResource
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
        if ($this->relationLoaded('warehouses') && $this->checkRelation('warehouseProducts.corridor')) {
            $this->warehouses->each(
                fn ($w) => $w->pivot->setRelation('corridor', $this->warehouseProducts->firstWhere('warehouse_id', $w->id)->corridor)
            );
        }

        return [
            'id' => $this->id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'name' => $this->name,
            'name_en' => $this->getTranslations('name', ['en'])['en'],
            'name_ar' => $this->getTranslations('name', ['ar'])['ar'],
            'description' => $this->description,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'total_quantity' => $this->total_quantity,
            'is_limited' => $this->is_limited,
            'limited_quantity' => $this->limited_quantity,
            'normal_discount' => $this->normal_discount,
            'market_price' => round($this->price - ($this->normal_discount * $this->price) / 100, 1),
            'price' => $this->price,
            'color' => $this->color,
            'taxes' => round($this->taxes, 1),
            'type' => [
                'value' => $this->type,
                'name' => ProductType::getStringValue($this->type),
            ],
            'items_number_in_packet' => $this->items_number_in_packet,
            'packets_number_in_package' => $this->packets_number_in_package,
            'quantity_sum_in_warehouses' => $this->whenLoaded('warehouses', fn () => $this->warehouses->sum('product_quantity')),
            'quantity_in_warehouse' => $this->whenPivotLoaded('warehouse_product', function () {
                return $this->warehouse_quantity;
            }, 0),
            'has_offer' => $this->offers()->where('type', 0)->exists(),
            'has_bonus' => $this->offers()->where('type', 1)->exists(),
            'manufacturing_type' => [
                'value' => $this->manufacturing_type,
                'name' => ProductManufacturingType::getStringValue($this->manufacturing_type),
            ],
            'selling_status' => [
                'value' => $this->selling_status,
                'name' => ProductSellingStatus::getStringValue($this->selling_status),
            ],
            'buying_status' => [
                'value' => $this->buying_status,
                'name' => ProductBuyingStatus::getStringValue($this->buying_status),
            ],
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'manufacture_company' => new ManufacturerResource($this->whenLoaded('manufacturer')),
            'activeIngredients' => new ActiveIngredientResourceCollection($this->whenLoaded('activeIngredients')),
            'alternatives' => new MinimizedProductResourceCollection($this->whenLoaded('alternatives')),
            'batches' => new BatchResourceCollection($this->whenLoaded('batches')),
            'offers' => new OfferResourceCollection($this->whenLoaded('offers')),
            'warehouses' => new WarehouseResourceCollection($this->whenLoaded('warehouses')),
        ];
    }
}
