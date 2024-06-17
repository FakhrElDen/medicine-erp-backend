<?php

namespace Modules\Order\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Cart\Entities\CartSubBatch;
use Modules\Client\Entities\Pharmacy;
use Modules\Order\Filters\ReturnFilter;
use Modules\Product\Entities\Product;
use Modules\User\Entities\User;
use Modules\Warehouse\Entities\Warehouse;

class Returns extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'returns';

    protected $fillable = [
        'order_id',
        'pharmacy_id',
        'warehouse_id',
        'created_by',
    ];

    protected $filter = ReturnFilter::class;

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cartSubBatches(): MorphToMany
    {
        return $this->morphedByMany(CartSubBatch::class, 'returnable')->withPivot('quantity', 'price', 'total', 'discount', 'reason', 'expired_at', 'operating_number');
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'returnable')->withPivot('quantity', 'price', 'total', 'discount', 'reason', 'expired_at', 'operating_number');
    }

    public function returnables()
    {
        return $this->hasMany(Returnables::class, 'returns_id');
    }
}
