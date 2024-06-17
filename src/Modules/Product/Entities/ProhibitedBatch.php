<?php

namespace Modules\Product\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Carbon;
use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Filters\ProhibitedBatchFilter;

class ProhibitedBatch extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'product_id',
        'operating_number',
        'expiry_date',
        'post_number',
        'post_reason',
    ];

    protected $filter = ProhibitedBatchFilter::class;

    public function getExpiryDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m');
    }

    public function setExpiryDateAttribute($value)
    {
        $this->attributes['expiry_date'] = $value . '-01';
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
