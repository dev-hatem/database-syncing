<?php

declare(strict_types=1);

namespace App\Traits;

use App\Events\SyncDatabase;

trait DatabaseSyncing
{
    use Sync;

    public static function boot()
    {
        static::saved(function ($model) {
            $model->triggerEloquentSyncEvent();
        });

        static::deleted(function ($model) {
            $model->triggerEloquentSyncEvent();
        });

        parent::boot();
    }

    public function triggerEloquentSyncEvent()
    {
        event(new SyncDatabase($this));
    }



}
