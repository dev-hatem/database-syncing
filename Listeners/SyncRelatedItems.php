<?php

namespace App\Listeners;

use App\Events\SyncManyToManyRelation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class SyncRelatedItems
{


    public function handle(SyncManyToManyRelation $event): void
    {
        $centralConnection = config('tenancy.database.central_connection');

        $query =  DB::connection($centralConnection)->table($event->model->getTable());

        $event->isDelete
            ? $query->where($event->model->getAttributes())->delete()
            : $query->updateOrInsert($event->model->getAttributes());
    }
}
