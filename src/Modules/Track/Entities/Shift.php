<?php

namespace Modules\Track\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Shift extends BaseModel
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'from',
        'to',
    ];

    public $translatable = ['name'];
}
