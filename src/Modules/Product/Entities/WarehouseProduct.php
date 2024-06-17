<?php

namespace Modules\Product\Entities;

use App\Models\BaseModel;
use Modules\Warehouse\Entities\Corridor;

class WarehouseProduct extends BaseModel
{
   
    protected $table = 'warehouse_product';

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'corridor_id',
        'stand',
        'shelf',
        'quantity',
    ];

    public function corridor(){
        return $this->belongsTo(Corridor::class);
    }
}
