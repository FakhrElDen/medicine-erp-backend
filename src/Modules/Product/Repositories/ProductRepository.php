<?php

namespace Modules\Product\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Batch;
use Modules\Product\Entities\Product;
use Modules\Product\Enums\OfferType;
use Modules\Product\Enums\SlatType;

class ProductRepository extends BaseRepository
{
    public function __construct(protected Product $model)
    {
        //
    }

    public function all($input)
    {
        return $this->model->query()->applyFilters($input)->applySorts($input)
            ->when(isset($input['quantity_more_than_zero']) && $input['quantity_more_than_zero'] == 1, function ($query) {
                $query->whereHas('batches', function ($query) {
                    $query->where('current_quantity', '!=', 0);
                });
            })->when(isset($input['quantity_more_than_zero']) && $input['quantity_more_than_zero'] == 0, function ($query) {
                $query->whereHas('batches', function ($query) {
                    $query->where('current_quantity', '==', 0);
                });
            })
            ->with([
                'manufacturer',
                'offers',
                'alternatives',
                'activeIngredients',
                'warehouses',
                'warehouseProducts.corridor',
                'batches', function ($query) use ($input) {
                    $query->whereHas('batchOperator', function ($query) use ($input) {
                        $query->applyFilters($input);
                    })->with([
                        'corridor',
                        'warehouse',
                        'supplier'
                    ]);
                },
            ])
            ->get();
    }

    public function view($input)
    {
        return $this->model->where('id', $input['product_id'])->with([
            'manufacturer',
            'offers',
            'alternatives',
            'activeIngredients',
            'warehouses',
            'warehouseProducts.corridor',
            'batches' => function ($query) use ($input) {
                $query->applyFilters($input)->with([
                    'subBatches' => ['corridor', 'warehouse', 'batchOperator.supplier'],
                ]);
            },
        ])->first();
    }

    // *Refactor
    public function listAllPaginated($input)
    {
        return $this->model->query()->applyFilters($input)->whereHas('batches')
            ->when(!empty($input['product_id']), function ($query) use ($input) {
                $query->where('id', $input['product_id']);
            })->when(isset($input['quantity_more_than_zero']) && $input['quantity_more_than_zero'] == 1, function ($query) {
                $query->whereHas('batches', function ($query) {
                    $query->where('current_quantity', '!=', 0);
                });
            })->when(isset($input['quantity_more_than_zero']) && $input['quantity_more_than_zero'] == 0, function ($query) {
                $query->whereHas('batches', function ($query) {
                    $query->where('current_quantity', '==', 0);
                });
            })->when(isset($input['sort_by']), function ($query) use ($input) {
                $direction = isset($input['direction']) ? $input['direction'] : 'asc';
                switch ($input['sort_by']) {
                    case 'price':
                        $query->orderBy('price', $direction);
                        break;
                    case 'quantity':
                        $query->addSelect([
                            'total_current_quantity' => Batch::whereColumn('batches.product_id', 'products.id')
                                ->selectRaw('SUM(batches.current_quantity)'),
                        ])->orderBy('total_current_quantity', $direction);
                        break;
                    case 'product_type':
                        $query->orderBy('type', $direction);
                        break;
                    case 'name_ar':
                        $query->orderBy('products.name->ar', $direction);
                        break;
                    case 'name_en':
                        $query->orderBy('products.name->en', $direction);
                        break;
                    case 'location':
                        $query->addSelect([
                            'corridor_number' => Batch::whereColumn('batches.product_id', 'products.id')
                                ->join('corridors', 'corridors.id', '=', 'batches.corridor_id')
                                ->select('corridors.number')
                                ->orderBy('batches.id', 'desc')
                                ->limit(1),
                        ])->orderBy('corridor_number', $direction);
                        break;
                    case 'manufacturer_en':
                        $query->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
                            ->orderBy('manufacturers.name->en', $direction)->select('products.*');
                        break;
                    case 'manufacturer_ar':
                        $query->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
                            ->orderBy('manufacturers.name->ar', $direction)->select('products.*');
                        break;
                }
            })->withWhereHas('batches', function ($query) use ($input) {
                $query->with('corridor', 'supplier', 'warehouse');
                if (isset($input['warehouse_id'])) {
                    $query->where('warehouse_id', $input['warehouse_id']);
                }
                if (isset($input['corridor_id'])) {
                    $query->where('corridor_id', $input['corridor_id']);
                }
                if (isset($input['shelf'])) {
                    $query->where('shelf', $input['shelf']);
                }
                if (isset($input['stand'])) {
                    $query->where('stand', $input['stand']);
                }
                if (isset($input['supplied_at'])) {
                    $query->whereDate('supplied_at', $input['supplied_at']);
                }
                if (isset($input['supplier_id'])) {
                    $query->where('supplier_id', $input['supplier_id']);
                }
            })->with(['manufacturer', 'warehouses', 'warehouseProducts.corridor'])->paginate(perPage: 10);
    }

    public function dropdown()
    {
        return $this->model->get();
    }

    /**
     * Update product
     */
    public function update($product, array $input)
    {
        $input['updated_by'] = Auth::user()->id;
        $product->update(Arr::except($input, ['active_ingredient_ids']));

        if (!empty($input['active_ingredient_ids'])) {
            $product->activeIngredients()->sync($input['active_ingredient_ids']);
        }

        if (!empty($input['warehouses'])) {
            $warehouse_data = collect($input['warehouses']);
            $product->warehouses()->sync($warehouse_data->keyBy('warehouse_id')->map(
                fn ($warehouse) => Arr::only($warehouse, ['corridor_id', 'stand', 'shelf'])
            ));
        }

        return true;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function checkOffer($input)
    {
        return $this->model->find($input['product_id'])
            ->withWhereHas('offers', function ($query) use ($input) {
                $query->where('quantity_for_offer', '<=', $input['quantity']);
            })->first();
    }

    public function shortage($input)
    {
        return $this->model->where('is_limited', 1)
            ->when(isset($input['nameShortage']), function ($query) use ($input) {
                $query->where('name', 'like', '%' . $input['nameShortage'] . '%');
            })->get();
    }

    public function bonus($input)
    {
        return $this->model->whereHas('offers', function ($query) {
            $query->where('type', OfferType::QUANTITY);
        })->when(isset($input['nameBonus']), function ($query) use ($input) {
            $query->where('name', 'like', '%' . $input['nameBonus'] . '%');
        })->whereHas('batches', function ($query) {
            $query->where('current_quantity', '!=', 0);
        })->with(['offers' => function ($query) {
            $query->where('type', OfferType::QUANTITY);
        }])->get();
    }

    public function percentageOfferSlatOne($input)
    {
        return $this->model->whereHas('offers', function ($query) {
            $query->where('type', OfferType::PERCENTAGE)->where('slat_type', SlatType::FIRST_SLAT);
        })->whereHas('batches', function ($query) {
            $query->where('current_quantity', '!=', 0);
        })->with(['offers' => function ($query) {
            $query->where('type', OfferType::PERCENTAGE)->where('slat_type', SlatType::FIRST_SLAT);
        }])->when(isset($input['search_offer_one']), function ($query) use ($input) {
            $query->where('name', 'like', '%' . $input['search_offer_one'] . '%');
        })->paginate(isset($input['pagination_number_one']) ? $input['pagination_number_one'] : 10, ['*'], 'slat_one_page');
    }

    public function percentageOfferSlatTwo($input)
    {
        return $this->model->whereHas('offers', function ($query) {
            $query->where('type', OfferType::PERCENTAGE)->where('slat_type', SlatType::SECOND_SLAT);
        })->whereHas('batches', function ($query) {
            $query->where('current_quantity', '!=', 0);
        })->with(['offers' => function ($query) {
            $query->where('type', OfferType::PERCENTAGE)->where('slat_type', SlatType::SECOND_SLAT);
        }])->when(isset($input['search_offer_two']), function ($query) use ($input) {
            $query->where('name', 'like', '%' . $input['search_offer_two'] . '%');
        })->paginate(isset($input['pagination_number_two']) ? $input['pagination_number_two'] : 10, ['*'], 'slat_two_page');
    }

    public function medicationAlternatives($input)
    {
        $product = $this->model->find($input['id']);

        $activeIngredientIds = $product->activeIngredients()->pluck('active_ingredient_id');

        return $this->model->whereHas('activeIngredients', fn ($q) => $q->whereIn('active_ingredients.id', $activeIngredientIds))
            ->with(['batches', 'offers' => function ($query) {
                $query->where('type', OfferType::QUANTITY);
            }])->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10);
    }

    public function relatedActiveIngredient($input)
    {
        $product = $this->model->find($input['id']);

        $familyIds = $product->activeIngredients->pluck('ingredient_families_id')->unique();

        return $this->model->whereHas('activeIngredients', function ($query) use ($familyIds) {
            $query->whereIn('active_ingredients.ingredient_families_id', $familyIds);
        })->withCount(['activeIngredients' => function ($query) use ($familyIds) {
            $query->whereIn('active_ingredients.ingredient_families_id', $familyIds);
        }])->having('active_ingredients_count', $familyIds->count())->with(['batches', 'offers' => function ($query) {
            $query->where('type', OfferType::QUANTITY);
        }])->paginate(isset($input['pagination_number']) ? $input['pagination_number'] : 10);
    }

    public function getProductByBarcode($barcode)
    {
        return $this->model->where('barcode', $barcode)->first();
    }
}
