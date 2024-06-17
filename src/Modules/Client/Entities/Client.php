<?php

namespace Modules\Client\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Carbon;
use Modules\Cart\Entities\Cart;
use Modules\Client\Enums\ClientType;
use Spatie\Translatable\HasTranslations;
use Modules\User\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Filters\ClientFilter;

class Client extends BaseModel
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'code',
        'phone_number',
        'type',
    ];

    protected $filter = ClientFilter::class;

    protected $appends = ['days_of_creation'];

    public $translatable = ['name'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->code) {
                $latestCode = self::latest()->first()->code ?? 'G0';
                $pattern = '/([a-zA-Z]+)(\d+)/';
                preg_match($pattern, $latestCode, $matches);
                $alphaPart = $matches[1] ?? 'G';
                $numericPart = $matches[2] ?? 1;
                $model->code = $alphaPart . ($numericPart + 1);
            }
        });

        static::created(function ($model) {
            if ($model->type == ClientType::SUPPLIER) {
                $userRepository = app(UserRepository::class);

                return $userRepository->createUserForClient($model->id, $model->code);
            }
        });
    }

    public function getDaysOfCreationAttribute()
    {
        $startDate = Carbon::parse($this->created_at);
        $endDate = Carbon::now();

        return $startDate->diffInDays($endDate);
    }

    public function pharmacies()
    {
        return $this->belongsToMany(Pharmacy::class, 'pharmacy_client');
    }

    public function cart()
    {
        return $this->belongsToMany(Cart::class, 'carts');
    }

    public function waitingList()
    {
        return $this->hasOne(WaitingList::class);
    }
}
