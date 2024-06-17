<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Product\Http\Requests\CheckOfferRequest;
use Modules\Product\Repositories\OfferRepository;

class OfferController extends BaseController
{
    public function __construct(protected OfferRepository $offerRepository)
    {
        $this->middleware('permission:sales_employee|free_delegate')->only(['check']);
    }

    public function check(CheckOfferRequest $request)
    {
        $data = $this->offerRepository->check($request->validated());

        return $this->apiResponse($data);
    }
}
