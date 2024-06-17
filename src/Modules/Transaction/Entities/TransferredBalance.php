<?php

namespace Modules\Transaction\Entities;

use App\Filters\CreatedAtFilter;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Entities\Pharmacy;
use Modules\User\Entities\User;

class TransferredBalance extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'from_pharmacy_id',
        'to_pharmacy_id',
        'user_id',
        'from_previous_balance',
        'to_previous_balance',
        'amount',
    ];

    protected $sorts = [
        'created_at' => CreatedAtFilter::class,
    ];

    public function from_pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'from_pharmacy_id', 'id');
    }

    public function to_pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'to_pharmacy_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
