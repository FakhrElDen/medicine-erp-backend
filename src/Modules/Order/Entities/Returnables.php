<?php

namespace Modules\Order\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\JoinClause;
use Modules\Cart\Entities\CartSubBatch;
use Modules\Product\Entities\Batch;
use Modules\Product\Entities\Product;

class Returnables extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'returnables';

    protected $fillable = [
        'returns_id',
        'returnable_type',
        'returnable_id',
        'quantity',
        'price',
        'discount',
        'total',
        'reason',
        'expired_at',
        'operating_number',
    ];

    public function return()
    {
        return $this->belongsTo(Returns::class, 'returns_id');
    }

    public function returnable()
    {
        return $this->morphTo();
    }

    public function parentBatch()
    {
        return $this->hasOne(Batch::class, 'operating_number', 'operating_number')->whereNull('parent_batch_id');
    }

    public function getProduct()
    {
        /**
         * we use getRelation instead of calling the relation directly to make sure
         * that it doesn't lazy load the relations and cause an n+1 issue
         * and the @ sign is for suppressing any warning when the relation is not found in the relations array
         */
        return match ($this->returnable_type) {
            Product::class => @$this->getRelation('returnable'),
            CartSubBatch::class => @$this->getRelation('returnable')->getRelation('cart')->getRelation('product'),
        };
    }

    public function scopeFilterByProductId($query, $product_id)
    {
        // note that the subQuery selects product_id from cart_sub_batches for all returnables,
        // even when the returnable_type is Product,
        // but it only uses it in the where clause when the returnable_type is CartSubBatch.

        $query->addSelect([
            'product_id' => CartSubBatch::join('carts', 'cart_sub_batch.cart_id', '=', 'carts.id')
                ->whereColumn('cart_sub_batch.id', 'returnables.returnable_id')
                ->select('carts.product_id')->limit(1),
        ])->where(function ($query) use ($product_id) {
            $query->where(fn ($q) => $q->where('returnable_type', Product::class)->where('returnable_id', $product_id))
                ->orWhere(fn ($q) => $q->where('returnable_type', CartSubBatch::class)->having('product_id', $product_id));
        });
    }

    public function scopeFilterByWarehouseId($query, $warehouse_id)
    {
        $query->addSelect([
            'warehouse_id' => CartSubBatch::join('carts', 'cart_sub_batch.cart_id', '=', 'carts.id')
                ->whereColumn('cart_sub_batch.id', 'returnables.returnable_id')
                ->select('carts.warehouse_id')->limit(1),
        ])->having('warehouse_id', $warehouse_id);
    }

    public function scopeJoinProducts($query)
    {
        $query->leftJoin('cart_sub_batch', function (JoinClause $join) {
            $join->on('cart_sub_batch.id', 'returnables.returnable_id')
                ->where('returnables.returnable_type', CartSubBatch::class);
        })->leftJoin('batches', 'batches.id', '=', 'cart_sub_batch.batch_id')
            ->leftJoin('products as cart_products', 'cart_products.id', '=', 'batches.product_id')
            ->leftJoin('products', function (JoinClause $join) {
                $join->on('products.id', 'returnables.returnable_id')
                    ->where('returnables.returnable_type', Product::class);
            })->selectRaw('COALESCE(cart_products.name, products.name) as product_name');
    }

    public function scopeJoinManufacturers($query)
    {
        $query->joinProducts()
            ->leftJoin('manufacturers as cart_manufacturers', 'cart_products.manufacturer_id', '=', 'cart_manufacturers.id')
            ->leftJoin('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
            ->selectRaw('COALESCE(cart_manufacturers.name, manufacturers.name) as manufacturer_name');
    }

    public function scopeJoinWarehouses($query)
    {
        $query->join('returns', 'returnables.returns_id', '=', 'returns.id')
            ->join('warehouses', 'returns.warehouse_id', '=', 'warehouses.id');
    }
}
