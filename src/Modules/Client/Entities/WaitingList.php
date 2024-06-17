<?php

namespace Modules\Client\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\User;

class WaitingList extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'sales_id',
        'pharmacy_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->sales_id = Auth::id();
        });
    }

    public function sales()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
}
