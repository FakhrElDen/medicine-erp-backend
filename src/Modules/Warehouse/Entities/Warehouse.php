<?php

namespace Modules\Warehouse\Entities;

use App\Models\BaseModel;
use Modules\Product\Entities\Batch;
use Modules\Cart\Entities\CartSubBatch;
use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\SubBatch;
use Modules\Warehouse\Database\factories\WarehouseFactory;

class Warehouse extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'address',
    ];

    protected static function newFactory()
    {
        return WarehouseFactory::new();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'warehouse_product')->withPivot('corridor_id', 'stand', 'shelf')
            ->addSelect([
                'warehouse_quantity' => SubBatch::
                // whereColumn('batches.product_id', 'warehouse_product.product_id')
                    whereColumn('sub_batches.warehouse_id', 'warehouse_product.warehouse_id')
                    // ->where('batches.storing_worker_id', '!=', null)
                    ->selectRaw('COALESCE(SUM(current_quantity), 0)'),
            ]);
    }

    public function subBatches()
    {
        return $this->hasMany(SubBatch::class);
    }

    public function cartSubBatch()
    {
        return $this->belongsToMany(CartSubBatch::class, 'batch_cart_warehouse');
    }

    public function settlementBatches()
    {
        return $this->hasMany(CartSubBatchWarehouse::class);
    }

    public function corridors()
    {
        return $this->hasMany(Corridor::class);
    }
}
