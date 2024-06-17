<?php

namespace Modules\Order\Entities;

use App\Models\BaseModel;
use App\Traits\HasMediaAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Modules\User\Entities\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Invoice extends BaseModel implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasMediaAttributes;

    protected $fillable = [
        'order_id',
        'printed_by',
        'bags_num',
        'fridges_num',
        'cartons_num',
        'invoices_num',
        'printed_num',
        'printed_at',
        'qr_code',
    ];

    protected $mediaAttributes = [
        'qr_code',
    ];

    public function getPrintedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function printedBy()
    {
        return $this->belongsTo(User::class, 'printed_by');
    }
}
