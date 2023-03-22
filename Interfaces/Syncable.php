<?php

namespace App\Interfaces;

interface Syncable
{
    public function getCentralModelName(): string;

    public function getSyncedAttributeNames(): array;
}
