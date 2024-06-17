<?php

namespace Modules\Purchase\Transformers;

use App\Traits\CheckNestedRelations;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Modules\Order\Enums\ReturnsReasons;
use Modules\Product\Enums\BatchHistoryType;
use Modules\Product\Transformers\ProductResource;
use Modules\User\Transformers\UserResource;
use Modules\Warehouse\Transformers\WarehouseResource;

class CartPurchaseReturnResource extends JsonResource
{
    use CheckNestedRelations;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request, $product_prices = null)
    {
        $product = $this->when(
            $this->checkRelation('cartPurchase.product'),
            fn () => new ProductResource($this->cartPurchase->product),
        );

        $price_after = $this->when(
            $this->checkRelation('cartPurchase'),
            fn () => $this->cartPurchase->public_price,
        );

        if ($product_prices && !$product instanceof MissingValue && !$price_after instanceof MissingValue) {
            $last_selling_price = $product_prices->where('type', BatchHistoryType::SALES)->last()?->price ?? $product->price;

            if ($last_selling_price == $price_after) {
                $price_before = $price_after;
            } else {
                $buying_prices = $product_prices->whereIn('type', [BatchHistoryType::PURCHASE, BatchHistoryType::SALES_RETURN]);

                $price_before = $buying_prices->where('price', $price_after)->isEmpty()
                    ? $last_selling_price
                    : $price_after;
            }
        }

        return [
            'id' => $this->id,
            'order_id' => $this->purchases_return_id,
            'supplier' => $this->when(
                $this->checkRelation('cartPurchase.purchase.supplier'),
                fn () => new UserResource($this->cartPurchase->purchase->supplier)
            ),
            'created_at' => $this->created_at,
            'product' => $product,
            'price_before' => $price_before,
            'price_after' => $price_after,
            'quantity_before' => null,
            'amount' => $this->quantity,
            'quantity_after' => null,
            'reason' => ReturnsReasons::getStringValue($this->reason),
            'batch' => null,
            'user' => $this->when(
                $this->checkRelation('purchasesReturn.createdBy'),
                fn () => new UserResource($this->purchasesReturn->createdBy)
            ),
            'warehouse' => $this->when(
                $this->checkRelation('cartPurchase.purchase.warehouse'),
                fn () => new WarehouseResource($this->cartPurchase->purchase->warehouse)
            ),
        ];
    }
}
