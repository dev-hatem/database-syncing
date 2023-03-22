<?php

namespace App\Listeners;

use App\Events\SyncDeletedItems;

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

        foreach ($items as $item){
            $centralModel::query()->where([
                $item->getGlobalIdentifierKeyName()          => $item->getGlobalIdentifierKey(),
                $centralModel->getCentralForeignKeyName()    => $item->getKey(),
            ])->delete();
        }
    }
}
