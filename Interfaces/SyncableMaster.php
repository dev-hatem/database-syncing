<?php

namespace App\Interfaces;

interface SyncableMaster
{
    public function getCentralForeignKeyName(): string;

}
