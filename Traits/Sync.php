<?php


namespace App\Traits;


trait Sync
{
    public function getGlobalIdentifierKey()
    {
        return tenant('id');
    }

    public function getGlobalIdentifierKeyName(): string
    {
        return 'tenant_id';
    }
}
