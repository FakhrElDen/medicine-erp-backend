<?php

namespace Modules\Product\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'created_by',
        'updated_by',
        'type',
        'quantity_for_offer',
        'offer_value',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_offer')->orderBy('quantity');
    }
}
