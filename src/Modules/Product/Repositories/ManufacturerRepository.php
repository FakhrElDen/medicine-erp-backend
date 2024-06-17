<?php

namespace Modules\Product\Repositories;

use App\Repositories\BaseRepository;
use Modules\Product\Entities\Manufacturer;

class ManufacturerRepository extends BaseRepository
{
    public function __construct(protected Manufacturer $model)
    {
    }

    public function get()
    {
        return $this->model->get();
    }
}
