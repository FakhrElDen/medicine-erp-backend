<?php

namespace Modules\Area\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Area\Http\Requests\IndexAreaRequest;
use Modules\Area\Repositories\AreaRepository;
use Modules\Area\Transformers\AreaResourceCollection;

class AreaController extends BaseController
{
    public function __construct(protected AreaRepository $areaRepository)
    {
        $this->middleware('permission:general')->only(['index']);
    }

    public function index(IndexAreaRequest $request)
    {
        $areas = $this->areaRepository->index($request->validated());

        return $this->apiResponse(new AreaResourceCollection($areas));
    }
}
