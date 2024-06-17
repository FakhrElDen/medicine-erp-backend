<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Entities\Order;
use Modules\Order\Transformers\WarehouseOrderResource;

class BulkPreparationOrdersCount implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithBroadcasting;

    /**
     * Create a new event instance.
     */
    public function __construct(public int $orders_count, public Order $order, public string $action)
    {
        $this->broadcastVia('redis');
    }

    public function broadcastWith()
    {
        return [
            'orders_count' => $this->orders_count,
            'order' => new WarehouseOrderResource($this->order),
            'action' => $this->action,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('bulk_preparing'),
        ];
    }
}
