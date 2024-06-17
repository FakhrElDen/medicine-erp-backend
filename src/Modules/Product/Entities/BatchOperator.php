<?php

namespace Modules\Product\Entities;

use App\Models\BaseModel;
use Modules\User\Entities\User;
use Illuminate\Support\Carbon;
use Modules\Product\Database\factories\BatchOperatorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Filters\BatchOperatorFilter;

class BatchOperator extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'storing_worker_id',
        'receiver_distributor_id',
        'receiver_reviewer_id',
        'supplier_id',
        'distributor_received_at',
        'reviewer_received_at',
        'sub_batch_id',
        'stored_at',
        'supplied_at',
        'created_by',
        'updated_by',
    ];

    protected $filter = BatchOperatorFilter::class;

    protected static function newFactory()
    {
        return BatchOperatorFactory::new();
    }

    public function getDistributorReceivedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : $value;
    }

    public function getStoredAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : $value;
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function receiverReviewer()
    {
        return $this->belongsTo(User::class, 'receiver_reviewer_id');
    }

    public function receiverDistributor()
    {
        return $this->belongsTo(User::class, 'receiver_distributor_id');
    }

    public function storingWorker()
    {
        return $this->belongsTo(User::class, 'storing_worker_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
