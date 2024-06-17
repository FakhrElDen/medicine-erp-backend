<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Product\Transformers\BatchTransferResourceCollection;
use Modules\Warehouse\Entities\BatchTransfer;

class TransfersCount implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithBroadcasting;

    public Collection $batch_transfer;

    /**
     * Create a new event instance.
     */
    public function __construct(public int $count, public string $action, Collection|BatchTransfer $batch_transfer)
    {
        $this->broadcastVia('redis');
        $this->batch_transfer = $batch_transfer instanceof BatchTransfer ? new Collection([$batch_transfer]) : $batch_transfer;
    }

    public function broadcastWith()
    {
        return [
            'count' => $this->count,
            'action' => $this->action,
            'batch_transfers' => new BatchTransferResourceCollection($this->batch_transfer->load('batch', 'transfer')),
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
            new Channel('transfers'),
        ];
    }
}
