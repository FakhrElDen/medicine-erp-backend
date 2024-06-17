<?php

namespace Modules\Cart\Observers;

use Modules\Cart\Entities\Cart;

class CartObserver
{
    /**
     * Listen to the Cart deleted event.
     *
     * @return void
     */
    public function deleted(Cart $cart)
    {
        $order = $cart->order()->first();
        if ($order && $order->cart->isEmpty()) {
            $order->forceDelete();
        }
    }
}
