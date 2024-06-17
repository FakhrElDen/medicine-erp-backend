<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Product\Enums\BatchHistoryType;
use Modules\Product\Repositories\BatchRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Transformers\BatchResource;
use Modules\Purchase\Enums\CartPurchaseStatus;
use Modules\Purchase\Http\Requests\InventoryingProductRequest;
use Modules\Purchase\Http\Requests\RemoveInventoriedRequest;
use Modules\Purchase\Repositories\CartPurchaseRepository;
use Modules\Purchase\Transformers\CartPurchaseResource;

class CartPurchaseController extends BaseController
{
    public function __construct(
        protected BatchRepository $batchRepository,
        protected ProductRepository $productRepository,
        protected CartPurchaseRepository $cartPurchaseRepository
    ) {
        $this->middleware('permission:reviewing_purchase_order')->only(['removeInventoried', 'inventorying']);
    }

    public function removeInventoried(RemoveInventoriedRequest $request)
    {
        $batch = $this->batchRepository->find($request->batch_id);
        $cartPurchase = $this->cartPurchaseRepository->find($batch->cart_purchase_id)->load('purchase', 'product');
        $cartPurchase->decrement('inventoried_quantity', $batch->current_quantity);
        $cartPurchase->decrement('inventoried_quantity_price', $batch->current_quantity * $cartPurchase->supply_price);
        $this->cartPurchaseRepository->update($batch->cart_purchase_id, ['status' => CartPurchaseStatus::NON_INVENTORIED]);
        $batch->delete();

        return $this->apiResponse(new CartPurchaseResource($cartPurchase));
    }

    /**
     * زرار أضافة فى مراجع الاستلامات
     * reviewing product and convert it to batches by receiving reviewer
     */
    public function inventorying(InventoryingProductRequest $request)
    {
        $product = $this->productRepository->find($request->only('product_id'))->first();

        $product->update([
            'items_number_in_packet'    => $request->items_number_in_packet,
            'packets_number_in_package' => $request->packets_number_in_package,
        ]);

        $sourceBatch = $this->batchRepository->all([
            'product_id'        => $request->product_id,
            'purchase_id'       => $request->purchase_id,
            'operating_number'  => $request->operating_number,
            'expired_at'        => $request->expired_at,
        ])->first();

        if ($sourceBatch) {
            $sourceBatch->increment('quantity', $request->quantity);
            $sourceBatch->increment('current_quantity', $request->quantity);
            $batch = $sourceBatch;
        } else {
            $batch = $this->batchRepository->store($request->validated(), $product);
        }

        $cartPurchase = $this->cartPurchaseRepository->find($request->only('cart_purchase_id'))->first();

        ($sourceBatch ?? $batch)->recordChangeInQuantity($request->quantity, BatchHistoryType::PURCHASE, $cartPurchase);

        if ($cartPurchase->quantity == $request->quantity + $cartPurchase->inventoried_quantity) {
            $this->cartPurchaseRepository->update($request->only('cart_purchase_id'), [
                'status'                        => CartPurchaseStatus::INVENTORIED,
                'inventoried_quantity'          => $cartPurchase->quantity,
                'inventoried_quantity_price'    => $cartPurchase->quantity * $cartPurchase->supply_price,
            ]);
        } else {
            $this->cartPurchaseRepository->update($request->only('cart_purchase_id'), [
                'status'                        => CartPurchaseStatus::SEMI_INVENTORIED,
                'inventoried_quantity'          => $request->quantity + $cartPurchase->inventoried_quantity,
                'inventoried_quantity_price'    => ($request->quantity + $cartPurchase->inventoried_quantity) * $cartPurchase->supply_price,
            ]);
        }

        return $this->apiResponse(new BatchResource($batch->load('product', 'cartPurchaseItem.purchase')));
    }
}
