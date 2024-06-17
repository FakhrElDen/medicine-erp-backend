<?php

namespace Modules\Order\Transformers;

use Modules\User\Transformers\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
      'id'                 => $this->id,
      'bags_num'           => $this->bags_num,
      'cartons_num'        => $this->cartons_num,
      'fridges_num'        => $this->fridges_num,
      'invoices_num'       => $this->invoices_num,
      'total'              => $this->invoices_num + $this->cartons_num + $this->fridges_num + $this->bags_num,
      'printed_num'        => $this->printed_num,
      'printed_at'         => $this->printed_at,
      'created_at'         => $this->created_at,
      'qr_code'            => $this->qr_code,
      'printed_by'         => new UserResource($this->whenLoaded('printedBy')),
      'order'              => new OrderResource($this->whenLoaded('order')),
    ];
  }
}
