<?php

namespace Modules\Setting\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Modules\Client\Entities\Client;
use Modules\Client\Entities\Pharmacy;
use Modules\Product\Filters\ComplainSort;
use Modules\Setting\Filters\ComplainFilter;
use Modules\User\Entities\User;
use Spatie\Permission\Models\Role;

class Complain extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'client_id',
        'pharmacy_id',
        'created_by',
        'body',
        'created_at',
        'user_id',
        'solver_id',
        'role_id',
    ];

    protected $filter = ComplainFilter::class;

    protected $sort = ComplainSort::class;
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function solver()
    {
        return $this->belongsTo(User::class, 'solver_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
