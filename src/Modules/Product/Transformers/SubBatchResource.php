<?php

namespace Modules\Product\Transformers;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Cart\Transformers\CartSubBatchResource;
use Modules\Purchase\Transformers\PurchaseResource;
use Modules\Warehouse\Transformers\CorridorResource;
use Modules\Warehouse\Transformers\WarehouseResource;
use Modules\Purchase\Transformers\CartPurchaseResource;

class SubBatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $currentMonth = Carbon::now();
        $expiryDate = Carbon::parse($this->expired_at ?? $this->parentBatch->expired_at);
        $status_flag = 0;

        if ($expiryDate->format('Y-m-d') <= $currentMonth->format('Y-m-d')) {
            $status = trans('product::message.expired');
        } elseif ($expiryDate->format('Y-m-d') <= $currentMonth->copy()->addMonths(2)->format('Y-m-d')) {
            $status = trans('product::message.expired_less_than_two_months');
        } else {
            $status_flag = 1;
            $status = trans('product::message.default_expired_message');
        }

        if ($status_flag == 1) {
            $expiryStatus = $expiryDate->addMonths(1)->diffInMonths($currentMonth) . ' ' . $status;
        } else {
            $expiryStatus = $status;
        }

        return [
            'id'                                => $this->id,
            'current_quantity'                  => $this->current_quantity,
            'expiry_status'                     => $expiryStatus,
            'production_date'                   => $this->production_date,
            'shelf'                             => $this->shelf,
            'stand'                             => $this->stand,
            'created_at'                        => $this->created_at,
            'cart_sub_batch'                    => $this->whenPivotLoaded('cart_sub_batch', fn () => new CartSubBatchResource($this->pivot)),
            'batch_transfer'                    => $this->whenPivotLoaded('batch_transfer', fn () => new BatchTransferResource($this->pivot)),
            'corridor'                          => new CorridorResource($this->whenLoaded('corridor')),
            'warehouse'                         => new WarehouseResource($this->whenLoaded('warehouse')),
            'purchase'                          => new PurchaseResource($this->whenLoaded('purchase')),
            'cart_purchase_item'                => new CartPurchaseResource($this->whenLoaded('cartPurchaseItem')),
            // 'main_location'                     => $this->product->getMainLocation(),
            // 'distributor_received_at'           => $this->distributor_received_at,
            // 'reviewer_received_at'              => $this->reviewer_received_at,
            // 'stored_at'                         => $this->stored_at,
            // 'supplied_at'                       => $this->supplied_at,
            // 'status'                            => isset($this->supplier) ? trans('product::message.not_return') : trans('product::message.return'),
            // 'supplier'                          => new UserResource($this->whenLoaded('supplier')),
            // 'created_by'                        => new UserResource($this->whenLoaded('createdBy')),
            // 'receiver_reviewer'                 => new UserResource($this->whenLoaded('receiverReviewer')),
            // 'receiver_distributor'              => new UserResource($this->whenLoaded('receiverDistributor')),
            // 'supplier'                          => new UserResource($this->whenLoaded('supplier')),
            // 'storing_worker'                    => new UserResource($this->whenLoaded('storingWorker')),
            // 'product'                           => new ProductResource($this->whenLoaded('product')),
            // 'original_batch'                    => new BatchResource($this->whenLoaded('originalBatch')),
            // 'updated_by'                        => new UserResource($this->whenLoaded('updatedBy')),
        ];
    }
}
