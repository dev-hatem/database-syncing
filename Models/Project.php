<?php

namespace App\Models;

use App\Interfaces\Syncable;
use App\Interfaces\SyncableMaster;
use App\Traits\DatabaseSyncing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model implements Syncable, SyncableMaster
{
    use HasFactory, DatabaseSyncing;
    protected $guarded = [];


    public function users()
    {
        return $this->belongsToMany(User::class,'project_user', 'project_id', 'user_id')
            ->using(SyncPivot::class);
    }

    public function getCentralModelName(): string
    {
        return self::class;
    }

    public function getSyncedAttributeNames(): array
    {
        return ['name', 'created_at', 'updated_at'];
    }

    public function getCentralForeignKeyName(): string
    {
        return 'project_id';
    }

}
