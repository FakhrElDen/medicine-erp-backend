<?php

namespace Modules\Area\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Area\Http\Requests\IndexCityRequest;
use Modules\Area\Repositories\CityRepository;
use Modules\Area\Transformers\CityResourceCollection;

class CityController extends BaseController
{
    public function __construct(protected CityRepository $cityRepository)
    {
        $this->middleware('permission:general')->only(['index']);
    }

    public function index(IndexCityRequest $request)
    {
        $cities = $this->cityRepository->index($request->validated());

        return $this->apiResponse(new CityResourceCollection($cities));
    }
}
