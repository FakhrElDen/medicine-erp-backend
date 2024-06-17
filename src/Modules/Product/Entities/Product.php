<?php

namespace Modules\Product\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Order\Entities\Returns;
use Modules\Product\Filters\ProductFilter;
use Modules\Product\Filters\ProductSort;
use Modules\Product\Database\factories\ProductFactory;
use Modules\Warehouse\Entities\Corridor;
use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Enums\WarehouseType;
use Spatie\Translatable\HasTranslations;

class Product extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;

    protected $fillable = [
        'created_by',
        'updated_by',
        'name',
        'description',
        'sku',
        'barcode',
        'total_quantity',
        'limited_quantity',
        'price',
        'taxes',
        'type',
        'normal_discount',
        'items_number_in_packet',
        'packets_number_in_package',
        'is_limited',
        'manufacturer_id',
        'manufacturing_type',
        'selling_status',
        'buying_status',
        'note',
    ];

    protected $filter = ProductFilter::class;

    protected $sort = ProductSort::class;

    public $translatable = ['name'];

    protected static function newFactory()
    {
        return ProductFactory::new();
    }

    public function setItemsNumberInPacketAttribute($value)
    {
        $this->attributes['items_number_in_packet'] = $value;
        $this->isDirty('items_number_in_packet') ? $this->save() : true;
    }

    public function setPacketsNumberInPackageAttribute($value)
    {
        $this->attributes['packets_number_in_package'] = $value;
        $this->isDirty('packets_number_in_package') ? $this->save() : true;
    }

    protected static function booted()
    {
        //
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'product_offer');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_product')->withPivot('corridor_id', 'stand', 'shelf')
            ->addSelect([
                'product_quantity' => SubBatch::
                whereColumn('sub_batches.warehouse_id', 'warehouse_product.warehouse_id')
                // ->whereColumn('batches.product_id', 'warehouse_product.product_id')
                    ->selectRaw('SUM(current_quantity)'),
                    // ->where('batches.storing_worker_id', '!=', null)
                'corridor_number' => Corridor::whereColumn('corridors.id', 'warehouse_product.corridor_id')
                    ->join('products', 'products.id', 'warehouse_product.product_id')
                    ->select('corridors.number'),
            ]);
    }

    public function warehouseProducts()
    {
        return $this->hasMany(WarehouseProduct::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'product_id')
        // ->where('current_quantity', '!=', 0)
        // ->where('storing_worker_id', '!=', null)
        // ->orderBy('expired_at', 'asc')
        ;
    }

    // public function batchOperator()
    // {
    //     return $this->hasMany(BatchOperator::class, 'product_id')
    //         // ->where('current_quantity', '!=', 0)
    //         // ->where('storing_worker_id', '!=', null)
    //         // ->orderBy('expired_at', 'asc')
    //         ;
    // }

    public function activeIngredients()
    {
        return $this->belongsToMany(ActiveIngredient::class, 'product_ingredients');
    }

    public function alternatives()
    {
        $instance = $this->newRelatedInstance(Product::class);

        $query = $instance->newQuery()
            ->join('product_ingredients', 'products.id', '=', 'product_ingredients.product_id')
            ->join('active_ingredients', 'product_ingredients.active_ingredient_id', '=', 'active_ingredients.id')
            ->join('active_ingredients as alternate_ingredients', 'active_ingredients.ingredient_families_id', '=', 'alternate_ingredients.ingredient_families_id')
            ->join('product_ingredients as alternate_pivot', 'alternate_ingredients.id', '=', 'alternate_pivot.active_ingredient_id')
            ->join('products as alternate_products', 'alternate_pivot.product_id', '=', 'alternate_products.id')
            ->select('alternate_products.id', 'alternate_products.name', 'product_ingredients.product_id');

        return $this->newHasMany(
            $query,
            $this,
            'product_ingredients.product_id',
            'id'
        );
    }

    public function returns(): MorphToMany
    {
        return $this->morphToMany(Returns::class, 'returnable');
    }

    public function getMainLocation(): ?array
    {
        if ($this->relationLoaded('warehouses')) {
            $warehouse = $this->warehouses->firstWhere('type', WarehouseType::MAIN);

            return [
                'corridor_id' => $warehouse->pivot->corridor_id,
                'number' => $warehouse->corridor_number,
                'stand' => $warehouse->pivot->stand,
                'shelf' => $warehouse->pivot->shelf,
            ];
        }

        return null;
    }
}
