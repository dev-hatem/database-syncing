<?php

namespace App\Listeners;

use App\Events\SyncUpdatedItems;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateGivenItems
{

    public function handle(SyncUpdatedItems $event): void
    {
        $items = $event->updatedItems;

        if ($items->isEmpty())
            return;

        $baseModel = $items->first();

        $centralModel = new ($baseModel->getCentralModelName());

        $centralConnection = config('tenancy.database.central_connection');

        foreach ($items as $item) {

            $syncedAttributes = array_intersect_key($item->getAttributes(), array_flip($item->getSyncedAttributeNames()));

            DB::connection($centralConnection)->query()->updateOrInsert([
                $centralModel->getCentralForeignKeyName()   => $item->getKey(),
                $item->getGlobalIdentifierKeyName()         => $item->getGlobalIdentifierKey()
            ], $syncedAttributes);
        }
    }
}
