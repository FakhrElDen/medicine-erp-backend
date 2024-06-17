<?php

namespace Modules\Product\Entities;

use App\Models\BaseModel;
use Modules\Product\Enums\BatchHistoryType;
use Modules\Warehouse\Entities\Transfer;
use Modules\Product\Database\factories\BatchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Modules\Product\Filters\BatchFilter;

class Batch extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'operating_number',
        'packet',
        'package',
        'quantity',
        'price',
        'expired_at',
    ];

    protected $filter = BatchFilter::class;

    protected static function newFactory()
    {
        return BatchFactory::new();
    }

    public function setExpiredAtAttribute($value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->format('Y-m');
        }

        $matches = preg_match('/^\d{4}-\d{2}$/', $value);

        if ($matches) {
            $this->attributes['expired_at'] = $value . '-01';
        } else {
            $this->attributes['expired_at'] = $value;
        }
    }

    public function getExpiredAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m') : $value;
    }

    public function subBatches()
    {
        return $this->hasMany(SubBatch::class, 'batch_id');
    }

    public function transfers()
    {
        return $this->belongsToMany(Transfer::class, 'batch_transfer', 'batch_id', 'transfer_id')
            ->withPivot([
                'id',
                'quantity_before_transfer',
                'quantity_transferred',
                'discount',
                'total',
                'transferred_at'
            ]);
    }

    public function originalBatch()
    {
        return $this->hasOneThrough(
            Batch::class,
            BatchHistory::class,
            'batch_id',
            'id',
            'id',
            'subject_id',
        )->where('batch_histories.type', BatchHistoryType::EDIT)
            ->where('batch_histories.amount', '>', '0');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
