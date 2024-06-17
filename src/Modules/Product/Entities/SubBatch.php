<?php

namespace Modules\Product\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Modules\Cart\Entities\Cart;
use Modules\Purchase\Entities\Purchase;
use Modules\Warehouse\Entities\Corridor;
use Modules\Purchase\Entities\CartPurchase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Database\factories\SubBatchFactory;
use Modules\Product\Filters\SubBatchFilter;
use Modules\Warehouse\Entities\Warehouse;

class SubBatch extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'warehouse_id',
        'corridor_id',
        'purchase_id',
        'cart_purchase_id',
        'shelf',
        'stand',
        'current_quantity',
        'discount',
        'production_date',
    ];

    protected $filter = SubBatchFilter::class;

    protected static function newFactory()
    {
        return SubBatchFactory::new();
    }

    public function batchHistories()
    {
        return $this->hasMany(BatchHistory::class);
    }
    
    /**
     * update current_quantity and create a batch_history record
     */
    public function updateQuantity(int $quantity, int $type, Model $subject = null)
    {
        $amount = $quantity - $this->current_quantity;

        $this->update(['current_quantity' => $quantity]);

        return $this->recordChangeInQuantity($amount, $type, $subject);
    }

    /**
     * Create a batch_history record when changing current_quantity
     */
    public function recordChangeInQuantity(int $amount, int $type, Model $subject = null, $second_user_id = null)
    {
        if ($amount != 0) {
            $product_quantity = $this->product?->warehouses()->firstWhere('warehouses.id', $this->warehouse_id)?->product_quantity;

            $this->batchHistories()->create([
                'user_id' => auth()->id(),
                'second_user_id' => $second_user_id,
                'type' => $type,
                'quantity_after' => $this->current_quantity,
                'warehouse_product_quantity_after' => $product_quantity,
                'amount' => $amount,
                'subject_id' => $subject->id ?? null,
                'subject_type' => $subject ? get_class($subject) : null,
            ]);
        }

        return $this;
    }

    public function batchOperator()
    {
        return $this->hasOne(BatchOperator::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function parentBatch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function corridor()
    {
        return $this->belongsTo(Corridor::class);
    }

    public function cart()
    {
        return $this->belongsToMany(Cart::class, 'cart_sub_batch', 'batch_id', 'cart_id')
            ->withPivot([
                'quantity',
                'returned_quantity',
                'status',
                'completed_at'
            ]);
    }

    public function cartPurchaseItem()
    {
        return $this->belongsTo(CartPurchase::class, 'cart_purchase_id');
    }
}
