<?php


namespace App\Models;


use App\Events\SyncManyToManyRelation;
use App\Traits\Sync;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SyncPivot extends Pivot
{
    use Sync;

    protected static function boot()
    {
        parent::boot();

        static::saved(function (Pivot $model){
            $pivot = new self();
            $model->{$pivot->getGlobalIdentifierKeyName()} = $pivot->getGlobalIdentifierKey();
            event(new SyncManyToManyRelation($model));
        });

        static::deleted(function (Pivot $model){
            $pivot = new self();
            $model->{$pivot->getGlobalIdentifierKeyName()} = $pivot->getGlobalIdentifierKey();
            event(new SyncManyToManyRelation($model, true));
        });
    }
}
