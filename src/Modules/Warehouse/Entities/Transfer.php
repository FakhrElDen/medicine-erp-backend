<?php

namespace Modules\Warehouse\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\Batch;
use Modules\User\Entities\User;

class Transfer extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'transfer_number',
        'transfer_to_warehouse_id',
        'transfer_from_warehouse_id',
        'created_by',
        'transferred_at',
    ];

    protected $teble = 'transfers';

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'transfer_from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'transfer_to_warehouse_id');
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_transfer', 'transfer_id', 'batch_id')->withPivot('id', 'quantity_before_transfer', 'quantity_transferred', 'discount', 'total', 'transferred_at');
    }

    public function batchTransfers()
    {
        return $this->hasMany(BatchTransfer::class);
    }
}
