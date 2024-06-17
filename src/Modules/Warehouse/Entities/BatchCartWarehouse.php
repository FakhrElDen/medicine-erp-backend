<?php

namespace Modules\Warehouse\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Cart\Entities\CartSubBatch;

/**
 * @property CartSubBatch $cartSubBatch
 */
class CartSubBatchWarehouse extends BaseModel
{
    use HasFactory;

    protected $table = 'batch_cart_warehouse';

    protected $fillable = ['cart_sub_batch_id', 'warehouse_id', 'returned_quantity'];

    public function cartSubBatch()
    {
        return $this->belongsTo(CartSubBatch::class, 'cart_sub_batch_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function scopeFilterByProductId($query, $product_id)
    {
        $query->addSelect([
            'product_id' => CartSubBatch::join('carts', 'cart_sub_batch.cart_id', '=', 'carts.id')
                ->whereColumn('cart_sub_batch.id', 'batch_cart_warehouse.cart_sub_batch_id')
                ->select('carts.product_id')->limit(1),
        ])->having('product_id', $product_id);
    }

    public function scopeFilterByWarehouseId($query, $product_id)
    {
        $query->addSelect([
            'from_warehouse_id' => CartSubBatch::join('carts', 'cart_sub_batch.cart_id', '=', 'carts.id')
                ->whereColumn('cart_sub_batch.id', 'batch_cart_warehouse.cart_sub_batch_id')
                ->select('carts.warehouse_id')->limit(1),
        ])->having('from_warehouse_id', $product_id);
    }

    public function scopeFilterByReviewerId($query, $reviewed_by)
    {
        $query->addSelect([
            'reviewed_by' => CartSubBatch::join('carts', 'cart_sub_batch.cart_id', '=', 'carts.id')
                ->join('orders', 'carts.order_id', '=', 'orders.id')
                ->whereColumn('cart_sub_batch.id', 'batch_cart_warehouse.cart_sub_batch_id')
                ->select('orders.reviewed_by')->limit(1),
        ])->having('reviewed_by', $reviewed_by);
    }

    public function scopeFilterByPharmacyId($query, $pharmacy_id)
    {
        $query->addSelect([
            'pharmacy_id' => CartSubBatch::join('carts', 'cart_sub_batch.cart_id', '=', 'carts.id')
                ->join('orders', 'carts.order_id', '=', 'orders.id')
                ->whereColumn('cart_sub_batch.id', 'batch_cart_warehouse.cart_sub_batch_id')
                ->select('orders.pharmacy_id')->limit(1),
        ])->having('pharmacy_id', $pharmacy_id);
    }

    public function scopeOrderId($query)
    {
        $query->addSelect([
            'order_id' => CartSubBatch::join('carts', 'cart_sub_batch.cart_id', '=', 'carts.id')
                ->whereColumn('cart_sub_batch.id', 'batch_cart_warehouse.cart_sub_batch_id')
                ->select('carts.order_id')->limit(1),
        ]);
    }
}
