<?php

namespace Modules\Transaction\Entities;

use App\Filters\CreatedAtFilter;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Entities\Client;
use Modules\Client\Entities\Pharmacy;
use Modules\User\Entities\User;

class Notification extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_id',
        'pharmacy_id',
        'notification_value',
        'type',
    ];

    protected $sorts = [
        'created_at' => CreatedAtFilter::class,
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
