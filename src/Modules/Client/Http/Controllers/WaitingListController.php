<?php

namespace Modules\Client\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Client\Http\Requests\AddToWaitingListRequest;
use Modules\Client\Repositories\WaitingListRepository;
use Modules\Client\Transformers\WaitingListResource;
use Modules\Client\Transformers\WaitingListResourceCollection;

class WaitingListController extends BaseController
{
    public function __construct(protected WaitingListRepository $waitingListRepository)
    {
        $this->middleware('permission:sales_employee|free_delegate')->only(['index', 'create']);
    }

    public function index()
    {
        $waitingLists = $this->waitingListRepository->get();

        return $this->apiResponse([
            'waiting_list' => new WaitingListResourceCollection($waitingLists),
            'waiting_list_number' => $waitingLists->count(),
        ]);
    }

    public function create(AddToWaitingListRequest $request)
    {
        $waitingList = $this->waitingListRepository->create($request->validated());
        $waitingLists = $this->waitingListRepository->get();

        return $this->apiResponse([
            'waiting_list' => new WaitingListResource($waitingList),
            'waiting_list_number' => $waitingLists->count(),
        ], trans('client::message.add_pharmacy_to_waiting_list'));
    }
}
