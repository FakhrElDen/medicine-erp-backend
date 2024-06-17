<?php

namespace Modules\Product\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActiveIngredient extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'ingredient_families_id',
    ];

    public function FamliyIngredient()
    {
        return $this->belongsToMany(IngredientFamily::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredients');
    }
}
