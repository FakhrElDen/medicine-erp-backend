<?php

namespace Modules\Warehouse\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\Batch;

class BatchTransfer extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'transfer_id',
        'batch_id',
        'quantity_before_transfer',
        'quantity_transferred',
        'discount',
        'total',
        'transferred_at',
        'created_at',
    ];

    protected $table = 'batch_transfer';

    public function batch(){
        return $this->belongsTo(Batch::class);
    }

    public function transfer(){
        return $this->belongsTo(Transfer::class);
    }

}
