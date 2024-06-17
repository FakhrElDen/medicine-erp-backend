<?php

namespace Modules\Transaction\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Transaction\Http\Requests\TransactionFilterRequest;
use Modules\Transaction\Repositories\CashPaymentRepository;
use Modules\Transaction\Transformers\CashPaymentResourceCollection;

class CashPaymentController extends BaseController
{
    public function __construct(protected CashPaymentRepository $CashPaymentRepository)
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
            $data = $this->CashPaymentRepository->get(['client_id' => $user->client_id] + $request->validated());
        } else {
            $data = $this->CashPaymentRepository->get($request->validated());
        }

        return $this->respondResource(new CashPaymentResourceCollection($data));
    }
}
