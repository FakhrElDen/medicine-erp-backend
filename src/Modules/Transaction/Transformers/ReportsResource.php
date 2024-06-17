<?php

namespace Modules\Transaction\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'orders_total'                   => $this['ordersTotal'] ?? null,
            'cash_payments_total'            => $this['cashPaymentsTotal'] ?? null,
            'purchases_total'                => $this['purchasesTotal'] ?? null,
            'notification_add'               => $this['notificationAdd'] ?? null,
            'transferred_balance_to'         => $this['transferredBalanceTo'] ?? null,
            'cash_Receives_total'            => $this['cashReceivesTotal'] ?? null,
            'returns_total'                  => $this['returnsTotal'] ?? null,
            'returns_purchases_total'        => $this['returnsTotal'] ?? null,
            'transferred_balance_from'       => $this['transferredBalanceFrom'] ?? null,
            'notification_discount'          => $this['notificationDiscount'] ?? null,
            'debt'                           => $this['debt'] ?? null,
        ];
    }
}
