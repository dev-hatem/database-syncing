<?php

namespace App\Listeners;

use App\Events\SyncDatabase;
use Illuminate\Support\Facades\DB;

class SyncWithCentralDatabase
{
    public function handle(SyncDatabase $event)
    {
        $syncedAttributes = array_intersect_key($event->model->getAttributes(), array_flip($event->model->getSyncedAttributeNames()));

        $centralConnection = config('tenancy.database.central_connection');

        $model = (new ($event->model->getCentralModelName()));

        DB::connection($centralConnection)->table($model->getTable())->updateOrInsert([
            $model->getCentralForeignKeyName()          => $event->model->getKey(),
            $event->model->getGlobalIdentifierKeyName() => $event->model->getGlobalIdentifierKey()
        ], $syncedAttributes);
    }

}
