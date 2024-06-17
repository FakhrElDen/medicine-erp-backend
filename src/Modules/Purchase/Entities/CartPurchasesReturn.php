<?php

namespace Modules\Purchase\Entities;

use App\Models\BaseModel;
use Modules\Order\Enums\ReturnsReasons;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartPurchasesReturn extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'purchases_return_id',
        'cart_purchase_id',
        'quantity',
        'reason',
        'total',
    ];

    public function getReasonAttribute($value)
    {
        return [
            'key'   => $value,
            'value' => ReturnsReasons::getStringValue($value)
        ];
    }
    
    public function cartPurchase()
    {
        return $this->belongsTo(CartPurchase::class);
    }

    public function purchasesReturn()
    {
        return $this->belongsTo(PurchasesReturn::class);
    }

    public function scopeAddSupplierName(Builder $query)
    {
        $query->addSelect([
            'supplier_name' => CartPurchase::whereColumn('cart_purchases.id', 'cart_purchases_returns.cart_purchase_id')
                ->join('purchases', 'purchases.id', '=', 'cart_purchases.purchase_id')
                ->join('users', 'users.id', '=', 'purchases.supplier_id')
                ->select('users.name')->limit(1),
        ]);
    }

    public function scopeAddWarehouseName(Builder $query)
    {
        $query->addSelect([
            'warehouse_name' => CartPurchase::whereColumn('cart_purchases.id', 'cart_purchases_returns.cart_purchase_id')
                ->join('purchases', 'purchases.id', '=', 'cart_purchases.purchase_id')
                ->join('warehouses', 'warehouses.id', '=', 'purchases.warehouse_id')
                ->select('warehouses.name')->limit(1),
        ]);
    }

    public function scopeAddUserName(Builder $query)
    {
        $query->addSelect([
            'user_name' => PurchasesReturn::whereColumn('purchases_returns.id', 'cart_purchases_returns.purchases_return_id')
                ->join('users', 'users.id', '=', 'purchases_returns.created_by')
                ->select('users.name')->limit(1),
        ]);
    }
}
