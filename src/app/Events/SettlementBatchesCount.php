<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Warehouse\Entities\CartSubBatchWarehouse;
use Modules\Warehouse\Transformers\SettlementResource;

class SettlementBatchesCount implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithBroadcasting;

    public Collection $batches;

    /**
     * Create a new event instance.
     */
    public function __construct(public int $count, public string $action, Collection|CartSubBatchWarehouse $batches)
    {
        $this->broadcastVia('redis');
        $this->batches = $batches instanceof CartSubBatchWarehouse ? new Collection([$batches]) : $batches;
    }

    public function broadcastWith()
    {
        $this->batches->loadMissing([
            'warehouse',
            'cartSubBatch' => fn ($q) => $q->with([
                'batch',
                'cart' => fn ($q) => $q->with(['order.pharmacy', 'order.reviewedBy', 'product', 'warehouse']),
            ]),
        ]);

        return [
            'count' => $this->count,
            'action' => $this->action,
            'settlement_batches' => SettlementResource::collection($this->batches),
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
            new Channel('settlement'),
        ];
    }
}
