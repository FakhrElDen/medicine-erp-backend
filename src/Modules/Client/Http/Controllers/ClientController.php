<?php

namespace Modules\Client\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Client\Http\Requests\ClientCreateRequest;
use Modules\Client\Http\Requests\ClientViewRequest;
use Modules\Client\Http\Requests\ClintIndexRequest;
use Modules\Client\Repositories\ClientRepository;
use Modules\Client\Transformers\ClientResource;
use Modules\Client\Transformers\ClientResourceCollection;
use Modules\Client\Transformers\MinimizedClientResourceCollection;

class ClientController extends BaseController
{
    public function __construct(protected ClientRepository $clientRepository)
    {
        $this->middleware('permission:listing_clients')->only(['index', 'dropdown']);
        $this->middleware('permission:create_client')->only(['store']);
    }

    public function index(ClintIndexRequest $request)
    {
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();
        if ($user->hasPermissionTo('free_delegate')) {
            $clients = $this->clientRepository->get(['id' => $user->client_id] + $request->validated());
        } else {
            $clients = $this->clientRepository->get($request->validated());
        }

        return $this->apiResponse(new ClientResourceCollection($clients));
    }

    public function view(ClientViewRequest $request)
    {
        $client = $this->clientRepository->view($request->validated());
        return $this->apiResponse(new ClientResource($client));
    }

    public function dropdown()
    {
        $clients = $this->clientRepository->dropdown();
        return $this->apiResponse(new MinimizedClientResourceCollection($clients));
    }

    public function store(ClientCreateRequest $request)
    {
        $this->clientRepository->store($request->validated());

        return $this->apiResponse(message: trans('client::message.create_client_message'));
    }
}
