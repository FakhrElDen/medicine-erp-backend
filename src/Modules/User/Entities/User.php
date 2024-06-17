<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Modules\Client\Entities\Client;
use Modules\Listing\Entities\Listing;
use Modules\Order\Entities\Order;
use Modules\Warehouse\Entities\Basket;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'password',
        'phone',
        'is_active',
        'shift',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guard_name = 'web';

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function salesOrders()
    {
        return $this->hasMany(Order::class, 'sales_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'created_by', 'id')->whereNull('sales_id');
    }

    public function listing()
    {
        return $this->belongsToMany(Listing::class, 'listing_user');
    }

    public function baskets()
    {
        return $this->hasMany(Basket::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
