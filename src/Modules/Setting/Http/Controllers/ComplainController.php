<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Setting\Http\Requests\ComplainRequest;
use Modules\Setting\Http\Requests\ShowSolverRequest;
use Modules\Setting\Http\Requests\SolveComplainsRequest;
use Modules\Setting\Http\Requests\SolverComplainRequest;
use Modules\Setting\Repositories\ComplainRepository;
use Modules\Setting\Transformers\ComplainResource;
use Modules\Setting\Transformers\ComplainResourceCollection;

class ComplainController extends BaseController
{
    public function __construct(protected ComplainRepository $complainRepository)
    {
        $this->middleware('permission:complain_crud')->only([
            'store',
            'update',
            'show',
            'solvedComplains',
            'unsolvedComplains'
        ]);
    }

    public function store(ComplainRequest $request)
    {
        $complain = $this->complainRepository->store($request->validated());

        return $this->apiResponse(new ComplainResource($complain));
    }

    public function update(SolverComplainRequest $request)
    {
        $this->complainRepository->updateSolverComplain($request->validated());

        return $this->apiResponse(message: trans('setting::message.update_complain_message'));
    }

    public function show(ShowSolverRequest $request)
    {
        $complain = $this->complainRepository->showComplain($request->validated());

        return $this->apiResponse(new ComplainResource($complain));
    }

    public function unsolvedComplains(SolveComplainsRequest $request)
    {
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();

        if ($user->hasPermissionTo('free_delegate')) {
            $complains = $this->complainRepository->unsolvedComplains(['client_id' => $user->client_id] + $request->validated());
        } else {
            $complains = $this->complainRepository->unsolvedComplains($request->validated());
        }

        return $this->respondResource(new ComplainResourceCollection($complains));
    }

    public function solvedComplains(SolveComplainsRequest $request)
    {
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();

        if ($user->hasPermissionTo('free_delegate')) {
            $complains = $this->complainRepository->solvedComplains(['client_id' => $user->client_id] + $request->validated());
        } else {
            $complains = $this->complainRepository->solvedComplains($request->validated());
        }

        return $this->respondResource(new ComplainResourceCollection($complains));
    }
}
