<?php

namespace Modules\Purchase\Entities;

use App\Models\BaseModel;
use Modules\Area\Entities\Area;
use Modules\Area\Entities\City;
use Modules\User\Entities\User;
use Modules\Track\Entities\Track;
use Modules\Client\Entities\Client;
use Modules\Product\Entities\Batch;
use Modules\Client\Entities\Pharmacy;
use Modules\Product\Entities\Product;
use Modules\Warehouse\Entities\Warehouse;
use Modules\Product\Entities\Manufacturer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Purchase\Filters\PurchaseFilter;
use Modules\Purchase\Filters\PurchaseSort;

class Purchase extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'manufacturer_id',
        'warehouse_id',
        'client_id',
        'pharmacy_id',
        'created_by',
        'track_id',
        'city_id',
        'area_id',
        'supplier_id',
        'reviewed_by',
        'total_quantity',
        'total_price',
        'last_balance',
        'current_balance',
        'purchase_number',
        'status',
        'type',
        'note',
        'reviewed_at',
        'created_at',
    ];

    protected $filter = PurchaseFilter::class;

    protected $sort = PurchaseSort::class;

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
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

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id', 'id');
    }

    public function cart()
    {
        return $this->hasMany(CartPurchase::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function return()
    {
        return $this->hasOne(PurchasesReturn::class);
    }
}
