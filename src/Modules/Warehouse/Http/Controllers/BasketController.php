<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use Modules\Order\Repositories\OrderRepository;
use Modules\Warehouse\Http\Requests\CreateBasketRequest;
use Modules\Warehouse\Repositories\BasketRepository;

class BasketController extends BaseController
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected BasketRepository $basketRepository
    ) {
        $this->middleware('permission:retail_preparation')->only(['create', 'delete']);
    }

    /**
     * We link order with corridors in order checkout
     * but we link it if corridor id is 1 because id 1 is ALL corridor
     * and there's no product in ALL corridor but we need to link it to return baskets in ALL corridor
     */
    public function create(CreateBasketRequest $request)
    {
        $basket = $this->basketRepository->create($request->validated());

        if ($basket->corridor->is_main_corridor == 1) {
            $order = $this->orderRepository->find($request->order_id);
            $order->corridors()->attach($request->corridor_id);
        }

        return $this->apiResponse($basket);
    }

    public function delete($basket_id)
    {
        $validatedData = Validator::make(['basket_id' => $basket_id], [
            'basket_id' => 'required|exists:baskets,id',
        ])->validate();

        $this->basketRepository->delete($validatedData['basket_id']);

        return $this->apiResponse();
    }
}
