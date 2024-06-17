<?php

namespace Modules\Area\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Entities\Pharmacy;
use Modules\Track\Entities\Track;
use Spatie\Translatable\HasTranslations;

class Area extends BaseModel
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
    ];

    public $translatable = ['name'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'track_area');
    }

    public function pharmacies()
    {
        return $this->hasMany(Pharmacy::class);
    }
}
