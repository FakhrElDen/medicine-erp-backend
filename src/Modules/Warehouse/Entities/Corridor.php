<?php

namespace Modules\Warehouse\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Cart\Entities\Cart;

class Corridor extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'number',
        'color',
        'is_main_corridor',
    ];

    public function baskets()
    {
        return $this->hasMany(Basket::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
}
