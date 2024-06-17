<?php

namespace Modules\Order\Entities;

use App\Models\BaseModel;
use Modules\Area\Entities\Area;
use Modules\Area\Entities\City;
use Modules\Cart\Entities\Cart;
use Modules\User\Entities\User;
use Modules\Track\Entities\Shift;
use Modules\Track\Entities\Track;
use Modules\Client\Entities\Client;
use Illuminate\Support\Facades\Auth;
use Modules\Client\Entities\Pharmacy;
use Modules\Warehouse\Entities\Basket;
use Modules\Warehouse\Entities\Corridor;
use Modules\Warehouse\Entities\Warehouse;
use Modules\Order\Traits\ReportQueryBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Order\Database\factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Filters\OrderFilter;
use Modules\Order\Filters\OrderSort;

class Order extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use ReportQueryBuilder;

    protected $fillable = [
        'sales_id',
        'client_id',
        'warehouse_id',
        'created_by',
        'prepared_by',
        'reviewed_by',
        'total_quantity',
        'total_price',
        'total_taxes',
        'total',
        'status',
        'shipping_type',
        'extra_discount',
        'extra_discount_condition',
        'note',
        'latitude',
        'longitude',
        'pharmacy_id',
        'track_id',
        'shift_id',
        'city_id',
        'deleted_by',
        'delivery_id',
        'area_id',
        'last_balance',
        'current_balance',
        'order_number',
        'returns',
        'completed_at',
        'closed_at',
        'total_after_extra_discount',
    ];

    protected $filter = OrderFilter::class;

    protected $sort = OrderSort::class;

    protected static function newFactory()
    {
        return OrderFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'id');
    }

    public function corridors()
    {
        return $this->belongsToMany(Corridor::class)
            ->orderBy('number', 'asc')->withPivot('completed_at', 'completed_by')
            ->select('corridors.*')
            ->distinct();
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by', 'id');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    public function delivery()
    {
        return $this->belongsTo(User::class, 'delivery_id', 'id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function baskets()
    {
        return $this->hasMany(Basket::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function returns()
    {
        return $this->hasMany(Returns::class);
    }
}
