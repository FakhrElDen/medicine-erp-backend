<?php

namespace Modules\Product\Repositories;

use App\Repositories\BaseRepository;
use Modules\Product\Entities\ProhibitedBatch;

class ProhibitedBatchRepository extends BaseRepository
{
    public function __construct(protected ProhibitedBatch $model)
    {
        //
    }

    public function allPaginated($input)
    {
        $local = app()->getLocale();

        return $this->model->query()->applyFilters()
            ->when(isset($input['product_name']), function ($query) use ($input, $local) {
                $query->whereHas('product', function ($query) use ($input, $local) {
                    $query->where("name->$local", 'like', '%' . $input['product_name'] . '%');
                });
            })->when(isset($input['manufacturer_id']), function ($query) use ($input) {
                $query->whereHas('product', function ($query) use ($input) {
                    $query->where('manufacturer_id', $input['manufacturer_id']);
                });
            })->with('product.manufacturer', 'createdBy')->paginate();
    }

    public function all($input)
    {
        return $this->model->query()->applyFilters($input)->with('product.manufacturer', 'createdBy')->get();
    }

    public function store($input)
    {
        // make accessor for created_by column instead of this line code
        // it's better if you make a global accessor for created_by
        $input['created_by'] = auth()->id();
        return $this->model->create($input);
    }
}
