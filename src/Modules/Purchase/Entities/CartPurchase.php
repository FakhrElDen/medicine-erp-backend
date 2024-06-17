<?php

namespace Modules\Purchase\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Area\Entities\Area;
use Modules\Area\Entities\City;
use Modules\Client\Entities\Client;
use Modules\Client\Entities\Pharmacy;
use Modules\Product\Entities\Manufacturer;
use Modules\Product\Entities\Product;
use Modules\Purchase\Filters\CartPurchaseFilter;
use Modules\Purchase\Filters\CartPurchaseSort;
use Modules\Track\Entities\Track;
use Modules\User\Entities\User;
use Modules\Warehouse\Entities\Warehouse;

class CartPurchase extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'created_by',
        'quantity',
        'inventoried_quantity',
        'inventoried_quantity_price',
        'public_price',
        'supply_price',
        'taxes',
        'discount',
        'discount_value	',
        'status',
        'subtotal',
        'total',
        'note',
    ];

    protected $filter = CartPurchaseFilter::class;

    protected $sort = CartPurchaseSort::class;

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

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

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id', 'id');
    }

    public function return()
    {
        return $this->hasMany(CartPurchasesReturn::class);
    }
}
