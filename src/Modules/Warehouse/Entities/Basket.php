<?php

namespace Modules\Warehouse\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\User\Entities\User;
use Modules\Warehouse\Filters\BasketFilter;

class Basket extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'corridor_id',
        'number',
        'status',
    ];

    protected $filter = BasketFilter::class;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = Auth::id();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function corridor()
    {
        return $this->belongsTo(Corridor::class);
    }
}
