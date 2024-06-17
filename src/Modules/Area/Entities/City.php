<?php

namespace Modules\Area\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Entities\Pharmacy;
use Spatie\Translatable\HasTranslations;

class City extends BaseModel
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
    ];

    public $translatable = ['name'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function pharmacies()
    {
        return $this->hasMany(Pharmacy::class);
    }
}
