<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Product\Entities\BatchHistory;
use Modules\Warehouse\Transformers\CorrectionResource;

class InventoryCount implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithBroadcasting;

    /**
     * Create a new event instance.
     */
    public function __construct(public int $count, public string $action, protected ?BatchHistory $batch_history = null)
    {
        $this->broadcastVia('redis');
    }

    public function broadcastWith()
    {
        return [
            'count' => $this->count,
            'action' => $this->action,
            'inventory' => $this->batch_history
                ? new CorrectionResource($this->batch_history->load('batch.product.warehouses', 'batch.warehouse', 'user'))
                : null,
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
            new Channel('inventory'),
        ];
    }
}
