<?php

namespace Modules\Transaction\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationResourceCollection extends ResourceCollection
{
    public $collects = 'Modules\Transaction\Transformers\NotificationResource';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $notification_value_sum = $this->collection->sum('notification_value');
        $total_process_number = $this->collection->count('*');

        return [
            'data' => $this->collection,
            'notification_value_sum' => $notification_value_sum,
            'total_process_number' => $total_process_number,
        ];
    }
}
