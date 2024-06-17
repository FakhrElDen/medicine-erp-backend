<?php

namespace Modules\Purchase\Entities;

use App\Models\BaseModel;
use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Purchase\Filters\PurchasesReturnFilter;

class PurchasesReturn extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'created_by',
        'supplier_id_number',
        'supplier_name',
        'note',
    ];

    protected $filter = PurchasesReturnFilter::class;

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function returnedItems()
    {
        return $this->belongsToMany(CartPurchase::class, 'cart_purchases_returns')->withPivot(['quantity', 'reason', 'total']);
    }
}
