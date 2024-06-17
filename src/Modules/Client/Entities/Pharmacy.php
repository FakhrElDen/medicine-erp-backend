<?php

namespace Modules\Client\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Carbon;
use Modules\Area\Entities\Area;
use Modules\Area\Entities\City;
use Modules\User\Entities\User;
use Modules\Order\Entities\Order;
use Modules\Track\Entities\Track;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasMediaAttributes;
use Modules\Order\Entities\Returns;
use Modules\Listing\Entities\Listing;
use Modules\Purchase\Entities\Purchase;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Transaction\Entities\CashPayment;
use Modules\Transaction\Entities\CashReceive;
use Modules\Transaction\Entities\Notification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Transaction\Entities\TransferredBalance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Filters\PharmacyFilter;
use Modules\Client\Filters\PharmacySort;

class Pharmacy extends BaseModel implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use HasMediaAttributes;
    use HasTranslations {
        HasMediaAttributes::setAttribute insteadof HasTranslations;
        HasTranslations::setAttribute as translatableSetAttribute;
    }

    protected $fillable = [
        'name',
        'email',
        'address',
        'phone_number',
        'optional_phone_number',
        'payment_type',
        'code',
        'debt_limit',
        'latitude',
        'longitude',
        'track_id',
        'city_id',
        'area_id',
        'extra_discount',
        'discount_slat',
        'expiration_extra_discount',
        'minimum_for_extra_discount',
        'doctor',
        'active',
        'commercial_registration_no',
        'license_no',
        'tax_card_no',
        'target',
        'minimum_target',
        'status',
        'payment_period',
        'delivery_id',
        'iterate_available_quantity',
        'note',
        'balance',
        'type',
        'call_shift',
        'all',
        'follow_up',
        'pharmacy_media',
        'location_url',
        'using_iterate_available_quantity_at',
    ];

    protected $filter = PharmacyFilter::class;

    protected $sorts = PharmacySort::class;

    protected $appends = ['has_client', 'days_of_creation'];

    public $translatable = ['name'];

    protected $mediaAttributes = [
        'pharmacy_media[]',
    ];

    public function getHasClientAttribute()
    {
        return $this->lists()->exists();
    }

    public function getDaysOfCreationAttribute()
    {
        $startDate = Carbon::parse($this->created_at);
        $endDate = Carbon::now();

        return $startDate->diffInDays($endDate);
    }

    protected function callFrom(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse(intval($value))->format('hA') : null,
            set: fn ($value) => $value ? Carbon::parse(intval($value))->format('H:i') : null,
        );
    }

    protected function callTo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse(intval($value))->format('hA') : null,
            set: fn ($value) => $value ? Carbon::parse(intval($value))->format('H:i') : null,
        );
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->code) {
                $lastCode = self::latest('id')->first()->code ?? 0;
                $model->code = $lastCode + 1;
            }
        });
    }

    public function delivery()
    {
        return $this->belongsTo(User::class, 'delivery_id', 'id');
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'pharmacy_client');
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function lastInvoice()
    {
        return $this->hasMany(Order::class)->latest();
    }

    public function lists()
    {
        return $this->belongsToMany(Listing::class, 'listing_pharmacy');
    }

    public function waitingList()
    {
        return $this->hasOne(WaitingList::class);
    }

    public function cashReceives()
    {
        return $this->hasMany(CashReceive::class);
    }

    public function cashPayments()
    {
        return $this->hasMany(CashPayment::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function transferredBalanceTo()
    {
        return $this->hasMany(TransferredBalance::class, 'to_pharmacy_id');
    }

    public function transferredBalanceFrom()
    {
        return $this->hasMany(TransferredBalance::class, 'from_pharmacy_id');
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }

    public function returns()
    {
        return $this->hasMany(Returns::class);
    }
}
