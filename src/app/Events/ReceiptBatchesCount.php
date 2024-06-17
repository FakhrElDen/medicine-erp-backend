<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Product\Transformers\BatchResource;

class ReceiptBatchesCount implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithBroadcasting;

    /**
     * Create a new event instance.
     */
    public function __construct(public array $batches_count, public string $action)
    {
        $this->broadcastVia('redis');
    }

    public function broadcastWith()
    {
        return [
            'batches_count' => array_map(function ($item) {
                return [
                    'count' => $item['count'],
                    'batches' => BatchResource::collection($item['batches']),
                    'corridor_id' => $item['corridor_id'],
                ];
            }, $this->batches_count),
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
            new Channel('receipt'),
        ];
    }
}
