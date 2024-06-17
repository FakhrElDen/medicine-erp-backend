<?php

namespace Modules\Track\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Track\Http\Requests\IndexTrackRequest;
use Modules\Track\Repositories\TrackRepository;
use Modules\Track\Transformers\TrackResourceCollection;

class TrackController extends BaseController
{
    public function __construct(protected TrackRepository $trackRepository)
    {
        $this->middleware('permission:general')->only(['index']);
    }

    public function index(IndexTrackRequest $request)
    {
        $areas = $this->trackRepository->index($request->validated());

        return $this->apiResponse(new TrackResourceCollection($areas));
    }
}
