<?php

namespace App\Listeners;

use App\Events\SyncDeletedItems;
use Illuminate\Support\Facades\DB;

class DeleteGivenItems
{

    /**
     * @param SyncDeletedItems $event
     */
    public function handle(SyncDeletedItems $event): void
    {
        $items = $event->deletedItems;

        if ($items->isEmpty())
            return;

        $baseModel = $items->first();

        $centralModel = new ($baseModel->getCentralModelName());

        $centralConnection = config('tenancy.database.central_connection');

        foreach ($items as $item){
            DB::connection($centralConnection)->query()->where([
                $item->getGlobalIdentifierKeyName()          => $item->getGlobalIdentifierKey(),
                $centralModel->getCentralForeignKeyName()    => $item->getKey(),
            ])->delete();
        }

    }
}
