<?php

namespace Modules\Product\Repositories;

use Modules\Product\Entities\Offer;
use Modules\Product\Enums\OfferType;

class OfferRepository
{
    public function __construct(protected Offer $model)
    {
    }

    public function check($input)
    {
        $offers = $this->model->where('quantity_for_offer', '<=', $input['quantity'])
            ->whereHas('products', function ($query) use ($input) {
                $query->where('product_id', $input['product_id']);
            })->get()->groupBy('type')->map(function ($group) use ($input) {
                $maxQuantity = $group->max('quantity_for_offer');

                return $group->filter(function ($offer) use ($maxQuantity, $input) {
                    $offer->type == OfferType::QUANTITY ? $offer->offer_value = intval(($input['quantity'] * $offer->offer_value) / $maxQuantity) : $offer->offer_value;

                    return $offer->quantity_for_offer == $maxQuantity;
                });
            })->flatten();

        $data['percentage'] = $offers->where('type', 0)->first() ? $offers->where('type', 0)->first()->offer_value : null;
        $data['quantity'] = $offers->where('type', 1)->first() ? $offers->where('type', 1)->first()->offer_value : null;

        return $data;
    }
}
