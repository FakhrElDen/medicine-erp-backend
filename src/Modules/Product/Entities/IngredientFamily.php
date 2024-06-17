<?php

namespace Modules\Product\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IngredientFamily extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
}
