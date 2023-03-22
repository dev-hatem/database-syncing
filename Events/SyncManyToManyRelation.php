<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SyncManyToManyRelation
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Model $model;
    public bool $isDelete;

    /**
     * SyncManyToManyRelation constructor.
     * @param Model $model
     * @param bool $isDelete
     */
    public function __construct(Model $model, $isDelete = false)
    {
        $this->model = $model;
        $this->isDelete = $isDelete;
    }

}
