<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SyncUpdatedItems
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Collection $updatedItems;

    /**
     * SyncDeletedItems constructor.
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->updatedItems = $collection;
    }
}
