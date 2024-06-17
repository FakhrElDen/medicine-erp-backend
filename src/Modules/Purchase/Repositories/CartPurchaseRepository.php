<?php

namespace Modules\Purchase\Repositories;

use App\Repositories\BaseRepository;
use Modules\Purchase\Entities\CartPurchase;

class CartPurchaseRepository extends BaseRepository
{
    public function __construct(protected CartPurchase $model)
    {
    }

    public function find($cart_purchase_id)
    {
        return $this->model->find($cart_purchase_id);
    }

    public function findBulk($cart_purchase_ids)
    {
        return $this->model->whereIn('id', $cart_purchase_ids)->get();
    }

    public function update($cart_purchase_id, $input)
    {
        return $this->model->where('id', $cart_purchase_id)->update($input);
    }

    public function bulkUpdate($ids, $input)
    {
        return $this->model->whereIn('id', $ids)->update($input);
    }
}
