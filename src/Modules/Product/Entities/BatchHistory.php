<?php

namespace Modules\Product\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Entities\CartSubBatch;
use Modules\Purchase\Entities\CartPurchase;
use Modules\User\Entities\User;
use Modules\Warehouse\Entities\BatchTransfer;

class BatchHistory extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'sub_batch_id',
        'user_id',
        'second_user_id',
        'quantity_after',
        'warehouse_product_quantity_after',
        'amount',
        'type',
        'subject_type',
        'subject_id',
    ];

    public function subBatch()
    {
        return $this->belongsTo(SubBatch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function secondUser()
    {
        return $this->belongsTo(User::class, 'second_user_id');
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function scopeAddQuantityBefore(Builder $query)
    {
        $query->addSelect(DB::raw(
            '`warehouse_product_quantity_after` - `amount` AS warehouse_product_quantity_before'
        ));
    }

    public function scopeAddExcessAndShortage(Builder $query)
    {
        $query->addSelect([
            DB::raw('CASE WHEN `amount` > 0 THEN `amount` ELSE NULL END AS excess'),
            DB::raw('CASE WHEN `amount` < 0 THEN ABS(`amount`) ELSE NULL END AS shortage'),
        ]);
    }

    public function scopeAddPharmacyName(Builder $query)
    {
        return $query->addSelect([
            'pharmacy_name' => CartSubBatch::whereColumn('cart_sub_batch.id', 'batch_histories.subject_id')
                ->join('carts', 'carts.id', '=', 'cart_sub_batch.cart_id')
                ->join('orders', 'orders.id', '=', 'carts.order_id')
                ->join('pharmacies', 'pharmacies.id', '=', 'orders.pharmacy_id')
                ->select('pharmacies.name->'.app()->getLocale())->limit(1),
        ]);
    }

    public function scopeAddFromWarehouseName(Builder $query)
    {
        return $query->addSelect([
            'from_warehouse_name' => BatchTransfer::whereColumn('batch_transfer.id', 'batch_histories.subject_id')
                ->join('transfers', 'transfers.id', '=', 'batch_transfer.transfer_id')
                ->join('warehouses', 'warehouses.id', '=', 'transfers.transfer_from_warehouse_id')
                ->select('warehouses.name')->limit(1),
        ]);
    }

    public function scopeAddSupplierName(Builder $query)
    {
        return $query->addSelect([
            'supplier_name' => CartPurchase::whereColumn('cart_purchases.id', 'batch_histories.subject_id')
                ->join('purchases', 'purchases.id', '=', 'cart_purchases.purchase_id')
                ->join('users', 'users.id', '=', 'purchases.supplier_id')
                ->select('users.name')->limit(1),
        ]);
    }
}
