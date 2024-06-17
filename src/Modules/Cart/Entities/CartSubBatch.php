<?php

namespace Modules\Cart\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Modules\Order\Entities\Returns;
use Modules\Product\Entities\Batch;

/**
 * @property SubBatch $batch
 */
class CartSubBatch extends BaseModel
{
    use HasFactory;

    protected $table = 'cart_sub_batch';
    
    protected $fillable = [
        'sub_batch_id',
        'cart_id',
        'quantity',
        'price',
        'total',
        'status',
        'color',
        'bonus',
        'discount',
        'completed_at',
        'inventoried_by',
        'inventoried_at',
        'returned_quantity',
    ];

    public function getInventoriedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function returns(): MorphToMany
    {
        return $this->morphToMany(Returns::class, 'returnable');
    }
}
