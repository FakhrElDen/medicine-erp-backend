<?php

namespace Modules\Listing\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Listing\Http\Requests\DeleteRequest;
use Modules\Listing\Http\Requests\IndexListingRequest;
use Modules\Listing\Http\Requests\ListingRequest;
use Modules\Listing\Http\Requests\UpdateListingRequest;
use Modules\Listing\Repositories\ListingRepository;
use Modules\Listing\Transformers\ListingResource;
use Modules\Listing\Transformers\ListingResourceCollection;

class ListingController extends BaseController
{
    public function __construct(protected ListingRepository $listingRepository)
    {
        $this->middleware('permission:list_crud|sales_manager')->only(['store', 'update', 'delete']);
        $this->middleware('permission:list_crud|sales_employee|free_delegate')->only(['index']);
    }

    public function index(IndexListingRequest $request)
    {
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();

        if ($user->hasPermissionTo('free_delegate')) {
            $data = $this->listingRepository->get(['client_id' => $user->client_id] + $request->validated());
        } else {
            $data = $this->listingRepository->get($request->validated());
        }

        return $this->respondResource(new ListingResourceCollection($data));
    }

    public function store(ListingRequest $request)
    {
        $listing = $this->listingRepository->create($request->validated());

        return $this->apiResponse(new ListingResource($listing));
    }

    public function delete(DeleteRequest $request)
    {
        $this->listingRepository->delete($request->validated());

        return $this->apiResponse(message: trans('listing::message.list_deleted'));
    }

    public function update(UpdateListingRequest $request)
    {
        $this->listingRepository->update($request->validated());

        return $this->apiResponse(message: trans('listing::message.list_updated'));
    }
}
