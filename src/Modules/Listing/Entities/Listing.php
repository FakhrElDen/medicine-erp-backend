<?php

namespace Modules\Listing\Entities;

use App\Filters\CreatedAtFilter;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Entities\Pharmacy;
use Modules\User\Entities\User;

class Listing extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    protected $sorts = [
        'created_at' => CreatedAtFilter::class,
    ];
    
    public function pharmacies()
    {
        return $this->belongsToMany(Pharmacy::class, 'listing_pharmacy');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'listing_user');
    }
}
