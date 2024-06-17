<?php

namespace Modules\Cart\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\Cart\Traits\SortTrait;
use Modules\Client\Entities\Client;
use Modules\Client\Entities\Pharmacy;
use Modules\Order\Entities\Order;
use Modules\Cart\Filters\CartFilter;
use Modules\Order\Filters\CartSort;
use Modules\Product\Entities\Batch;
use Modules\Product\Entities\Product;
use Modules\Product\Enums\ProductColor;
use Modules\User\Entities\User;
use Modules\Warehouse\Entities\Corridor;
use Modules\Warehouse\Entities\Warehouse;

class Cart extends BaseModel
{
    use HasFactory;
    use SortTrait;

    protected $fillable = [
        'product_id',
        'client_id',
        'pharmacy_id',
        'warehouse_id',
        'order_id',
        'track_id',
        'shift_id',
        'corridor_id',
        'prepared_by',
        'product_discount',
        'created_by',
        'quantity',
        'taxes',
        'price',
        'total',
        'subtotal',
        'discount_value',
        'client_discount_difference',
        'client_discount_difference_value',
        'note',
        'bonus',
        'color',
        'discount',
        'status',
        'completed_at',
        'cart_number',
        'created_at',
    ];

    protected $filter = CartFilter::class;

    protected $sorts = CartSort::class;

    protected static function booted()
    {
        static::creating(function ($model) {

            if (!$model->created_by) {
                $model->created_by = Auth::id();
            }

            $model->created_by = Auth::id();

            $settings = collect(Cache::get('settings'));
            $highPrice = $settings->firstWhere('key', 'high_price')->value;
            if ($model->price >= $highPrice) {
                $model->color = ProductColor::getStringValue(ProductColor::HIGH_PRICE);
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function subBatches()
    {
        return $this->belongsToMany(CartSubBatch::class, 'cart_sub_batch', 'cart_id', 'sub_batch_id')->withPivot([
            'id',
            'quantity',
            'returned_quantity',
            'status',
            'price',
            'discount',
            'total',
            'bonus',
            'color',
            'inventoried_by',
            'completed_at',
            'inventoried_at',
        ]);
    }

    // public function cartSubBatches()
    // {
    //     return $this->hasMany(CartSubBatch::class);
    // }

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by', 'id');
    }

    public function corridor()
    {
        return $this->belongsTo(Corridor::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
