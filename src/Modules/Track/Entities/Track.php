<?php

namespace Modules\Track\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Modules\Area\Entities\Area;
use Modules\Client\Entities\Pharmacy;
use Modules\User\Entities\User;
use Spatie\Translatable\HasTranslations;

class Track extends BaseModel
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
    ];

    public $translatable = ['name'];

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'track_area');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'track_user');
    }

    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'shift_track')->orderByRaw('ABS(TIMEDIFF(`from`, CURTIME())) ASC')
            ->where('from', '>=', Carbon::now()->toDateString());
    }

    public function pharmacies()
    {
        return $this->hasMany(Pharmacy::class);
    }
}
