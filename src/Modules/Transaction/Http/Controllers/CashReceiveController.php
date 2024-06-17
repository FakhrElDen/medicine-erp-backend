<?php

namespace Modules\Transaction\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Transaction\Http\Requests\TransactionFilterRequest;
use Modules\Transaction\Repositories\CashReceiveRepository;
use Modules\Transaction\Transformers\CashReceiveResourceCollection;

class CashReceiveController extends BaseController
{
    public function __construct(protected CashReceiveRepository $CashReceiveRepository)
    {
        $this->middleware('permission:client_transactions|sales_employee')->only(['index']);
    }

    public function index(TransactionFilterRequest $request)
    {
        /**
         * @var \Modules\User\Entities\User $user
         */
        $user = auth()->user();
        if ($user->hasPermissionTo('free_delegate')) {
            $data = $this->CashReceiveRepository->get(['client_id' => $user->client_id] + $request->validated());
        } else {
            $data = $this->CashReceiveRepository->get($request->validated());
        }

        return $this->respondResource(new CashReceiveResourceCollection($data));

        return $this->apiResponse([
            'data' => new CashReceiveResourceCollection($data['data']),
            'total_amount' => $data['data']->sum('received_amount'),
            'pagination' => $data['pagination'],
        ]);
    }
}
